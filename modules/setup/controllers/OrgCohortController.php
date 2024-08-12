<?php

namespace app\modules\setup\controllers;

use Yii;
use yii\web\Controller;
use app\models\OrgCohort;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\search\OrgCohortSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;

/**
 * OrgCohortController implements the CRUD actions for OrgCohort model.
 */
class OrgCohortController extends Controller
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
     * Lists all OrgCohort models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgCohortSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgCohort model.
     * @param int $cohort_id Cohort ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cohort_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cohort_id),
        ]);
    }

    /**
     * Creates a new OrgCohort model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgCohort();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {

                if ($model->load($this->request->post())) {
                    $event = OrgCohort::find()
                    ->where(['cohort_year' => $model->cohort_year])
                        ->andWhere(['=', 'adm_start_date', $model->adm_start_date])
                        ->andWhere(['=', 'adm_end_date', $model->adm_end_date])
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
        }
    }

    /**
     * Updates an existing OrgCohort model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cohort_id Cohort ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cohort_id)
    {
        $model = $this->findModel($cohort_id);

        if ($this->request->isPost) {
            if($model->load($this->request->post()) && $model->save()) {
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => "Cohort {$model->cohort_id} updated successfully",
                        'closeButton' => null,
                    ]);
                    $autoSync = new AutoSynchronize();
                    if ($autoSync->bulkSync(get_class($model))) {
                        return $this->redirect(['index']);
                    }
                }
            }
            var_dump($model->getErrors());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgCohort model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cohort_id Cohort ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cohort_id)
    {
        $this->findModel($cohort_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgCohort model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cohort_id Cohort ID
     * @return OrgCohort the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cohort_id)
    {
        if (($model = OrgCohort::findOne(['cohort_id' => $cohort_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
