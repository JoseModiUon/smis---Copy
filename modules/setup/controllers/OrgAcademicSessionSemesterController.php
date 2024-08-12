<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\OrgAcademicSessionSemester;
use app\models\search\OrgAcademicSessionSemesterSearch;
use app\modules\setup\utils\DuplicateChecker;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use yii\web\ServerErrorHttpException;

/**
 * OrgAcademicSessionSemesterController implements the CRUD actions for OrgAcademicSessionSemester model.
 */
class OrgAcademicSessionSemesterController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all OrgAcademicSessionSemester models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgAcademicSessionSemesterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgAcademicSessionSemester model.
     * @param int $acad_session_semester_id Acad Session Semester ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($acad_session_semester_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($acad_session_semester_id),
        ]);
    }

    /**
     * Creates a new OrgAcademicSessionSemester model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgAcademicSessionSemester();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {

                $dpCheck = new DuplicateChecker();
                $dpCheck->insert(OrgAcademicSessionSemester::class, $this, [
                    ['acad_session_id' => $this->request->post('OrgAcademicSessionSemester')['acad_session_id']],
                    ['semester_code' => $this->request->post('OrgAcademicSessionSemester')['semester_code']],
                ]);
                if ($models = $dpCheck->getObjectModel()) {
                    $autoSync = new AutoSynchronize([$models]);
                    $autoSync->save();
                }
                $transaction->commit();
                $spTransaction->commit();
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        } catch (\Throwable $th) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Updates an existing OrgAcademicSessionSemester model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $acad_session_semester_id Acad Session Semester ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($acad_session_semester_id)
    {
        $model = $this->findModel($acad_session_semester_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Item updated successfully",
                    'closeButton' => null,
                ]);
                $autoSync = new AutoSynchronize();
                if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgAcademicSessionSemester model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $acad_session_semester_id Acad Session Semester ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($acad_session_semester_id)
    {
        $this->findModel($acad_session_semester_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgAcademicSessionSemester model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $acad_session_semester_id Acad Session Semester ID
     * @return OrgAcademicSessionSemester the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($acad_session_semester_id)
    {
        if (($model = OrgAcademicSessionSemester::findOne(['acad_session_semester_id' => $acad_session_semester_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
