<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgProgLevel;
use app\models\search\OrgProgLevelSearch;
use app\modules\setup\utils\AutoSynchronize;
use app\modules\setup\utils\DuplicateChecker;
use kartik\growl\Growl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * OrgProgLevelController implements the CRUD actions for OrgProgLevel model.
 */
class OrgProgLevelController extends Controller
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
     * Lists all OrgProgLevel models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgLevelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgLevel model.
     * @param int $programme_level_id Programme Level ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($programme_level_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($programme_level_id),
        ]);
    }

    /**
     * Creates a new OrgProgLevel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgLevel();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {
                $dpCheck = new DuplicateChecker();
                $dpCheck->insert(OrgProgLevel::class, $this, [
                    ['programme_level_name' => $this->request->post('OrgProgLevel')['programme_level_name']]

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
     * Updates an existing OrgProgLevel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $programme_level_id Programme Level ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($programme_level_id)
    {
        $model = $this->findModel($programme_level_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "$model->programme_level_name updated successfully",
                    'closeButton' => null,
                ]);
                $autoSync = new AutoSynchronize();
                if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                }
            }
            //  return $this->redirect(['view', 'programme_level_id' => $model->programme_level_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgLevel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $programme_level_id Programme Level ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($programme_level_id)
    {
        $this->findModel($programme_level_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgLevel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $programme_level_id Programme Level ID
     * @return OrgProgLevel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($programme_level_id)
    {
        if (($model = OrgProgLevel::findOne(['programme_level_id' => $programme_level_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
