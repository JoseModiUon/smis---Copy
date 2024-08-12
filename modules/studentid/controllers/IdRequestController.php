<?php

namespace app\modules\studentid\controllers;

use app\modules\studentid\models\search\StudentIdSearch;
use app\modules\studentid\models\StudentId;
use app\modules\studentid\models\StudentIdDetails;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * IdRequestController implements the CRUD actions for StudentId model.
 */
class IdRequestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all StudentId models.
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new StudentIdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Updates an existing StudentId model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            //extract specific fields to allow only updating of those values
            $post = Yii::$app->request->post('StudentId');
            $remarks = $post['id_remarks'];
            $status = $post['id_status'];

            $model->id_remarks = $remarks;
            $model->id_status = $status;

            if ($model->save()) {
                //add details to student id details
                $studentIdDetails = new StudentIdDetails();
                $studentIdDetails->student_id_serial_no = $model->student_id_serial_no;
                $studentIdDetails->student_id_status = $model->id_status;
                $studentIdDetails->remarks = $remarks;
                $studentIdDetails->status_date = date('Y-m-d');

                $studentIdDetails->save();

                Yii::$app->session->setFlash('success', 'ID status successfully updated to ' . $model->id_status);

                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }


    /**
     * Finds the StudentId model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentId the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): StudentId
    {
        if (($model = StudentId::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested record does not exist.');
    }
}
