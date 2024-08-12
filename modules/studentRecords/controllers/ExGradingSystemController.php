<?php

namespace app\modules\studentRecords\controllers;

use app\models\ExGradingSystem;
use app\models\search\ExGradingSystemSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * ExGradingSystemController implements the CRUD actions for ExGradingSystem model.
 */
class ExGradingSystemController extends Controller
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
     * Lists all ExGradingSystem models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExGradingSystemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExGradingSystem model.
     * @param int $grading_system_id Grading System ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($grading_system_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($grading_system_id),
        ]);
    }

    /**
     * Creates a new ExGradingSystem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ExGradingSystem();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    $autoSync = new AutoSynchronize([$model]);
                    $autoSync->save();

                    $transaction->commit();
                    $spTransaction->commit();

                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => 'Academic Session Created!',
                        'closeButton' => null,
                    ]);
                    return $this->redirect('index');
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
     * Updates an existing ExGradingSystem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $grading_system_id Grading System ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($grading_system_id)
    {
        $model = $this->findModel($grading_system_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'grading_system_id' => $model->grading_system_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExGradingSystem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $grading_system_id Grading System ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($grading_system_id)
    {
        $this->findModel($grading_system_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExGradingSystem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $grading_system_id Grading System ID
     * @return ExGradingSystem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($grading_system_id)
    {
        if (($model = ExGradingSystem::findOne(['grading_system_id' => $grading_system_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
