<?php

namespace app\modules\setup\controllers;  

use app\models\ExMode;
use app\models\search\ExModeSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * ExModeController implements the CRUD actions for ExMode model.
 */
class ExModeController extends Controller
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
     * Lists all ExMode models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExModeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExMode model.
     * @param int $exam_mode_id Exam Mode ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($exam_mode_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($exam_mode_id),
        ]);
    }

    /**
     * Creates a new ExMode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();


        try {
            $model = new ExMode();

            if ($this->request->isPost) {
	
                if ($model->load($this->request->post()) && $model->save()) {

                    $autoSync = new AutoSynchronize([$model]);
                    $autoSync->save();

                    $transaction->commit();
                    $spTransaction->commit();

                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => 'Exam mode created!',
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

        }catch (\Throwable $th) {
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
     * Updates an existing ExMode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $exam_mode_id Exam Mode ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($exam_mode_id)
    {
        $model = $this->findModel($exam_mode_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Exam mode updated successfully",
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
     * Deletes an existing ExMode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $exam_mode_id Exam Mode ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($exam_mode_id)
    {
        $this->findModel($exam_mode_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExMode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $exam_mode_id Exam Mode ID
     * @return ExMode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($exam_mode_id)
    {
        if (($model = ExMode::findOne(['exam_mode_id' => $exam_mode_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
