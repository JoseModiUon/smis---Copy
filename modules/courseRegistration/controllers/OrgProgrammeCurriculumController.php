<?php

namespace app\modules\courseRegistration\controllers;

use app\models\ExGradingSystem;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgProgrammes;
use app\models\OrgSemesterType;
use app\models\portal_student\OrgProgCurrSemester;
use app\models\search\OrgProgrammeCurriculumSearch;
use app\modules\setup\utils\AutoSynchronize;
use app\modules\setup\utils\DuplicateChecker;
use kartik\growl\Growl;
use yii\web\ServerErrorHttpException;

/**
 * OrgProgrammeCurriculumController implements the CRUD actions for OrgProgrammeCurriculum model.
 */
class OrgProgrammeCurriculumController extends Controller
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
     * Lists all OrgProgrammeCurriculum models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgrammeCurriculumSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgrammeCurriculum model.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curriculum_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curriculum_id),
        ]);
    }

    /**
     * Creates a new OrgProgrammeCurriculum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {


        $model = new OrgProgrammeCurriculum();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {

                $dpCheck = new DuplicateChecker();
                $dpCheck->insert(OrgProgrammeCurriculum::class, $this, [
                    ['ilike', 'prog_cur_prefix',  $this->request->post('OrgProgrammeCurriculum')['prog_cur_prefix'] . '%', false],
                    ['prog_id' => $this->request->post('OrgProgrammeCurriculum')['prog_id']],
                    ['grading_system_id' => $this->request->post('OrgProgrammeCurriculum')['grading_system_id']],
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
     * Updates an existing OrgProgrammeCurriculum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curriculum_id)
    {
        $model = $this->findModel($prog_curriculum_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Programme Curriculum updated successfully",
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
     * Deletes an existing OrgProgrammeCurriculum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curriculum_id)
    {
        $this->findModel($prog_curriculum_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgrammeCurriculum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return OrgProgrammeCurriculum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curriculum_id)
    {
        if (($model = OrgProgrammeCurriculum::findOne(['prog_curriculum_id' => $prog_curriculum_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
