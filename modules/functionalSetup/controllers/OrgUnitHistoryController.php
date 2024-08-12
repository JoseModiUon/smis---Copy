<?php

namespace app\modules\functionalSetup\controllers;
use Yii;
use app\models\OrgUnitHistory;
use app\models\search\OrgUnitHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgUnitHistoryController implements the CRUD actions for OrgUnitHistory model.
 */
class OrgUnitHistoryController extends Controller
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
     * Lists all OrgUnitHistory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgUnitHistorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgUnitHistory model.
     * @param int $org_unit_history_id Org Unit History ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($org_unit_history_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($org_unit_history_id),
        ]);
    }

    /**
     * Creates a new OrgUnitHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgUnitHistory();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', ' Unit History Created!');
                return $this->redirect(['index']);
                //return $this->redirect(['view', 'org_unit_history_id' => $model->org_unit_history_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgUnitHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $org_unit_history_id Org Unit History ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($org_unit_history_id)
    {
        $model = $this->findModel($org_unit_history_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', ' Unit History Updated!');
            return $this->redirect(['index']);
         //   return $this->redirect(['view', 'org_unit_history_id' => $model->org_unit_history_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgUnitHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $org_unit_history_id Org Unit History ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($org_unit_history_id)
    {
        $this->findModel($org_unit_history_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgUnitHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $org_unit_history_id Org Unit History ID
     * @return OrgUnitHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($org_unit_history_id)
    {
        if (($model = OrgUnitHistory::findOne(['org_unit_history_id' => $org_unit_history_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
