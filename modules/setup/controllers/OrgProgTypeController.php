<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgProgType;
use app\models\search\OrgProgTypeSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * OrgProgTypeController implements the CRUD actions for OrgProgType model.
 */
class OrgProgTypeController extends Controller
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
     * Lists all OrgProgType models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgType model.
     * @param int $prog_type_id Prog Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_type_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_type_id),
        ]);
    }

    /**
     * Creates a new OrgProgType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgType();
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
                        'message' => 'Programme Type Created!',
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
     * Updates an existing OrgProgType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_type_id Prog Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_type_id)
    {
        $model = $this->findModel($prog_type_id);

        if ($this->request->isPost && $model->load($this->request->post())) {


            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => 'model->prog_type_name updated successfully',
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
     * Deletes an existing OrgProgType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_type_id Prog Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_type_id)
    {
        $this->findModel($prog_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_type_id Prog Type ID
     * @return OrgProgType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_type_id)
    {
        if (($model = OrgProgType::findOne(['prog_type_id' => $prog_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
