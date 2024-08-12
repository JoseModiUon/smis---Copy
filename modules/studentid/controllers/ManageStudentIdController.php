<?php

namespace app\modules\studentid\controllers;

use app\models\portal\SmisportalSmStudentId;
use app\models\portal\SmisportalSmStudentIdDetail;
use app\models\portal\SmisportalSmStudentIdRequest;
use app\models\portal\SmisportalSmStudentIdStatus;
use app\models\SmStudentId;
use app\modules\studentid\models\IdRequestStatus;
use app\modules\studentid\models\portal\SmisPortalIdRequestStatus;
use app\modules\studentid\models\search\StudentIdRequestSearch;
use app\modules\studentid\models\search\StudentIdSearch;
use app\modules\studentid\models\StudentId;
use app\modules\studentid\models\StudentIdDetails;
use app\modules\studentid\models\StudentIdRequest;
use app\modules\studentid\models\StudentIdStatus;
use Yii;
use yii\db\DataReader;
use yii\db\Exception;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ManageStudentIdController implements the CRUD actions for StudentIdRequest model.
 */
class ManageStudentIdController extends Controller
{
//    public $layout = 'domain';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'issue-ready-id' => ['get', 'post'],
                    'print-bulk' => ['post']
                ],
            ],
        ];
    }

    /**
     * Lists all StudentIdRequest models.
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $searchModel = new StudentIdRequestSearch();
        $dataProvider = $searchModel->searchIdRequests(Yii::$app->request->queryParams);

        $this->view->title = 'Pending ID requests';

        return $this->render('id-request/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionIssue(): string
    {

        $searchModel = new StudentIdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->view->title = 'Issue printed IDs';

        return $this->render('issue-id/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return string
     * @throws Exception
     */
    public function actionIssued(): string
    {

        $searchModel = new StudentIdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, StudentIdStatus::ID_ACTIVE);

        $this->view->title = 'View issued student ids';

        return $this->render('issue-id/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     */
    public function actionIssueReadyId($id): Response|string
    {
        $smisStudentIdDetails = new StudentIdDetails();

        if ($smisStudentIdDetails->load(Yii::$app->request->post())) {
            $smisStudentIdDetails->student_id_status = StudentIdStatus::ID_ISSUED;
            $smisStudentIdDetails->status_date = new Expression('CURRENT_TIMESTAMP');

            $smisTransaction = Yii::$app->db->beginTransaction();
            $portalTransaction = Yii::$app->db2->beginTransaction();

            $smisStudentId = SmStudentId::findOne($id);
            $smisIdStatus = new StudentIdStatus();
            $portalIdStatus = new SmisportalSmStudentIdStatus();
            $portalStudentId = SmisportalSmStudentId::findOne($id);
            $portalStudentIdDetail = new SmisportalSmStudentIdDetail();


            $smisStudentIdDetails->student_id_serial_no = $id;
            $smisStudentIdDetails->stud_id_det_id = $smisStudentId->student_id_serial_no;

            $smisIdStatus->id_status_no = $id;
            $smisIdStatus->student_id_serial_no = $id;

            $portalIdStatus->id_status_no = $id;
            $portalIdStatus->student_id_serial_no = $id;


            $portalStudentIdDetail->student_id_serial_no = $smisStudentId->student_id_serial_no;;
            $portalStudentIdDetail->load(['SmisportalSmStudentIdDetail' => $smisStudentIdDetails->attributes]);


            if ($smisStudentIdDetails->save() && $portalStudentIdDetail->save()) {
                //update the overall status in portal and system
                if ($smisStudentId != null) {
                    $smisStudentId->id_status = StudentIdStatus::ID_ACTIVE;
                    $portalStudentId->id_status = StudentIdStatus::ID_ACTIVE;
                    $smisIdStatus->status_name = StudentIdStatus::ID_ACTIVE;
                    $portalIdStatus->status_name = StudentIdStatus::ID_ACTIVE;

                    $portalStudentId->validate();
                    $smisStudentId->validate();
                    $smisIdStatus->validate();
                    $portalIdStatus->validate();

                    if ($smisIdStatus->save() && $portalIdStatus->save()) {
                        if ($smisStudentId->save() && $portalStudentId->save()) {
                            $smisTransaction->commit();
                            $portalTransaction->commit();
                            Yii::$app->session->setFlash('success', "Student ID issued successfully");
                            return $this->redirect(['active-id/print-id',
                                'id' => $smisStudentIdDetails->student_id_serial_no,
                            ]);
                        }
                    }
                }

                Yii::$app->session->setFlash('error', "Student ID not issued successfully");
                $smisTransaction->rollBack();
                $portalTransaction->rollBack();

            }


            $errors = [
                $smisStudentIdDetails->errors,
                $portalStudentIdDetail->errors,
                $smisStudentId->errors,
                $portalStudentId->errors
            ];

            $smisTransaction->rollBack();
            $portalTransaction->rollBack();
            Yii::$app->session->setFlash('error', "Student ID not issued successfully " . Json::encode($errors));
            return Json::encode([
                'smisStudent' => $smisStudentId->errors,
                'portalStudent' => $portalStudentIdDetail->errors,
                'smisId' => $smisStudentIdDetails->errors,
                'portalId' => $portalStudentIdDetail->errors
            ]);
        }

        $this->view->title = 'Issue student id';
        return $this->render('issue-id/issue-ready-id', [
            'model' => $smisStudentIdDetails
        ]);
    }


    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException|Exception
     * @throws \Exception
     */
    public function actionPrintSingle(int $id): string|Response
    {
        $smisTransaction = Yii::$app->db->beginTransaction();
        $portalTransaction = Yii::$app->db2->beginTransaction();
        //let us check for the data


        $smsIdDetails = $this->findBySql($id);

        //updated id request status
        $requestStatus = IdRequestStatus::getStatusId();
        $requestStatusId = ArrayHelper::getValue($requestStatus, 0, 0);
        $smisIdRequest = $this->findModel($id, $requestStatusId);

        $studentIdModel = new StudentId();
        $studentIdModel->request_id = $smisIdRequest->request_id;
        $studentIdModel->student_id_serial_no = $smisIdRequest->request_id;
        $studentIdModel->barcode = $smisIdRequest->request_id;
        $studentIdModel->student_prog_curr_id = $smsIdDetails['student_prog_curr_id'];
        $studentIdModel->printing_date = new Expression('CURRENT_TIMESTAMP');
        $studentIdModel->issuance_date = new Expression('CURRENT_TIMESTAMP');
        $studentIdModel->valid_from = $smsIdDetails['start_date'];
        $studentIdModel->valid_to = $smsIdDetails['end_date'];
        $studentIdModel->id_status = StudentIdStatus::ID_READY;
        $studentIdModel->id_remarks = StudentIdStatus::ID_READY;

        $portalIdModel = new SmisportalSmStudentId();
        $portalIdModel->load(['SmisportalSmStudentId' => $studentIdModel->attributes]);
        $portalIdRequest = SmisportalSmStudentIdRequest::findOne($id);

        $portalIdModel->student_prog_curr_id = $smisIdRequest->student_prog_curr_id;

        $studentIdModel->validate();
        $portalIdModel->validate();


        if ($studentIdModel->save() && $portalIdModel->save()) {
            $newStatus = IdRequestStatus::getStatusId(IdRequestStatus::CLOSED);
            $portalStatus = SmisPortalIdRequestStatus::getStatusId(IdRequestStatus::CLOSED);
            $smisIdRequest->status_id = ArrayHelper::getValue($newStatus, 0, 0);
            $portalIdRequest->status_id = ArrayHelper::getValue($portalStatus, 0, 0);

            if ($smisIdRequest->save() && $portalIdRequest->save()) {
                $smisTransaction->commit();
                $portalTransaction->commit();

                Yii::$app->session->setFlash('success', "Student ID issued successfully");
                return $this->redirect(['issue']);
            }
        }


        $errors = [
            $studentIdModel->errors,
            $portalIdModel->errors,
            $smisIdRequest->errors
        ];
        $smisTransaction->rollBack();
        $portalTransaction->rollBack();
        Yii::$app->session->setFlash('error', "There was an  error printing selected id: " . Json::encode($errors));
        return $this->redirect(['index']);

    }

    /**
     * @return false|string|Response
     */
    public function actionPrintBulk(): Response|bool|string
    {
        $selectedIds = Yii::$app->request->post('selection', []);
        if (count($selectedIds) < 2) {
            //redirect back to index
            Yii::$app->session->setFlash('error', "Please select at least 2 records for batch printing");
            return $this->redirect(['index']);
        }
        return json_encode($selectedIds);
    }


    /**
     * Finds the StudentIdRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|bool|DataReader
     * @throws Exception if the model cannot be found
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findBySql(int $id): DataReader|bool|array
    {
        if (($model = StudentIdRequest::findOneByPk($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested id request record does not exist.');
    }

    /**
     * Finds the StudentIdRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param int $statusId
     * @return StudentIdRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id, int $statusId): StudentIdRequest
    {
        if (($model = StudentIdRequest::findOne([
                'request_id' => $id,
                'status_id' => $statusId
            ])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested record does not exist.');
    }
}
