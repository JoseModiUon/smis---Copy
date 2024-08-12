<?php

namespace app\modules\courseRegistration\controllers;

use app\models\OrgProgCurrUnit;
use app\models\search\OrgProgCurrUnitSearch;
use app\modules\setup\utils\AutoSynchronize;
use app\modules\setup\utils\DuplicateChecker;
use kartik\growl\Growl;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * OrgProgCurrUnitController implements the CRUD actions for OrgProgCurrUnit model.
 */
class OrgProgCurrUnitController extends Controller
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
     * Lists all OrgProgCurrUnit models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCurrUnitSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgCurrUnit model.
     * @param int $prog_curriculum_unit_id Prog Curriculum Unit ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curriculum_unit_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curriculum_unit_id),
        ]);
    }

    /**
     * Creates a new OrgProgCurrUnit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrUnit();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {
                $dpCheck = new DuplicateChecker();
                $dpCheck->insert(OrgProgCurrUnit::class, $this, [
                    ['org_unit_history_id' => $this->request->post('OrgProgCurrUnit')['org_unit_history_id']],
                    ['prog_curriculum_id' => $this->request->post('OrgProgCurrUnit')['prog_curriculum_id']],
                ]);

                if ($models = $dpCheck->getObjectModel()) {
                    $autoSync = new AutoSynchronize([$models]);

                    $autoSync->save();
                }
                $transaction->commit();
                $spTransaction->commit();
        dd($autoSync->save());


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
     * Updates an existing OrgProgCurrUnit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_unit_id Prog Curriculum Unit ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curriculum_unit_id)
    {
        $model = $this->findModel($prog_curriculum_unit_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Programme Curriculum Unit updated successfully",
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
     * Deletes an existing OrgProgCurrUnit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_unit_id Prog Curriculum Unit ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curriculum_unit_id)
    {
        $this->findModel($prog_curriculum_unit_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCurrUnit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curriculum_unit_id Prog Curriculum Unit ID
     * @return OrgProgCurrUnit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curriculum_unit_id)
    {
        if (($model = OrgProgCurrUnit::findOne(['prog_curriculum_unit_id' => $prog_curriculum_unit_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
