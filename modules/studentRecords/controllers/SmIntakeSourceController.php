<?php

namespace app\modules\studentRecords\controllers;

use app\models\SmIntakeSource;
use app\models\search\SmIntakeSourceSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * SmIntakeSourceController implements the CRUD actions for SmIntakeSource model.
 */
class SmIntakeSourceController extends Controller
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
     * Lists all SmIntakeSource models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmIntakeSourceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmIntakeSource model.
     * @param int $source_id Source ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($source_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($source_id),
        ]);
    }

    /**
     * Creates a new SmIntakeSource model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmIntakeSource();
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
                        'message' => 'Intake Source Created!',
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
     * Updates an existing SmIntakeSource model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $source_id Source ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($source_id)
    {
        $model = $this->findModel($source_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Intake Source updated successfully",
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
     * Deletes an existing SmIntakeSource model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $source_id Source ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($source_id)
    {
        $this->findModel($source_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmIntakeSource model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $source_id Source ID
     * @return SmIntakeSource the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($source_id)
    {
        if (($model = SmIntakeSource::findOne(['source_id' => $source_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
