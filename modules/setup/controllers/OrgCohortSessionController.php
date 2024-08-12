<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\OrgCohortSession;
use yii\web\NotFoundHttpException;
use app\models\search\OrgCohortSessionSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use yii\web\ServerErrorHttpException;

/**
 * OrgCohortSessionController implements the CRUD actions for OrgCohortSession model.
 */
class OrgCohortSessionController extends Controller
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
     * Lists all OrgCohortSession models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgCohortSessionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgCohortSession model.
     * @param int $cohort_session_id Cohort Session ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cohort_session_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cohort_session_id),
        ]);
    }

    /**
     * Creates a new OrgCohortSession model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgCohortSession();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {
            
                if ($model->load($this->request->post())) {
                    $event = OrgCohortSession::find()
                    ->where(['cohort_id' => $model->cohort_id])
                        ->andWhere(['prog_curriculum_semester_id' => $model->prog_curriculum_semester_id])
                    ->one();
                    if (!$event) {
                        if (!$model->save()) {
                            Yii::$app->getSession()->setFlash('', [
                                'type' => Growl::TYPE_DANGER,
                                'icon' => 'bi bi-x-circle-fill',
                                'message' => 'Could not add cohort. Please try again.',
                                'closeButton' => null,
                            ]);
                        }
                        $autoSync = new AutoSynchronize([$model]);
                        $autoSync->save();

                        $transaction->commit();
                        $spTransaction->commit();
                        Yii::$app->getSession()->setFlash('', [
                            'type' => Growl::TYPE_SUCCESS,
                            'icon' => 'bi bi-check-circle-fill',
                            'message' => "Cohort added successfully",
                            'closeButton' => null,
                        ]);
    
                        return $this->redirect(['index']);
                    } else {
                        Yii::$app->getSession()->setFlash('', [
                            'type' => Growl::TYPE_WARNING,
                            'icon' => 'bi bi-exclamation-circle-fill',
                            'message' => 'Cohort already exists.',
                            'closeButton' => null,
                        ]);
                        return $this->redirect(['index']);
                    }
                }
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
     * Updates an existing OrgCohortSession model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cohort_session_id Cohort Session ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cohort_session_id)
    {
        $model = $this->findModel($cohort_session_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Cohort Session {$model->cohort_session_name} updated successfully",
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
     * Deletes an existing OrgCohortSession model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cohort_session_id Cohort Session ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cohort_session_id)
    {
        $this->findModel($cohort_session_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgCohortSession model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cohort_session_id Cohort Session ID
     * @return OrgCohortSession the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cohort_session_id)
    {
        if (($model = OrgCohortSession::findOne(['cohort_session_id' => $cohort_session_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
