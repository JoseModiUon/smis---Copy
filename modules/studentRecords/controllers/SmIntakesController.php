<?php

namespace app\modules\studentRecords\controllers;

use app\models\SmIntakes;
use app\models\search\SmIntakesSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * SmIntakesController implements the CRUD actions for SmIntakes model.
 */
class SmIntakesController extends Controller
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
     * Lists all SmIntakes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmIntakesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmIntakes model.
     * @param int $intake_code Intake Code
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($intake_code)
    {
        return $this->render('view', [
            'model' => $this->findModel($intake_code),
        ]);
    }

    /**
     * Creates a new SmIntakes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmIntakes();
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
                        'message' => 'Intake Created!',
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
     * Updates an existing SmIntakes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $intake_code Intake Code
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($intake_code)
    {
        $model = $this->findModel($intake_code);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Intake updated successfully",
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
     * Deletes an existing SmIntakes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $intake_code Intake Code
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($intake_code)
    {
        $this->findModel($intake_code)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmIntakes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $intake_code Intake Code
     * @return SmIntakes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($intake_code)
    {
        if (($model = SmIntakes::findOne(['intake_code' => $intake_code])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
