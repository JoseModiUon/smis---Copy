<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgProgCurrOption;
use app\models\search\OrgProgCurrOptionSearch;
use kartik\growl\Growl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgProgCurrOptionController implements the CRUD actions for OrgProgCurrOption model.
 */
class OrgProgCurrOptionController extends Controller
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
     * Lists all OrgProgCurrOption models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCurrOptionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgCurrOption model.
     * @param int $option_id Option ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($option_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($option_id),
        ]);
    }

    /**
     * Creates a new OrgProgCurrOption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrOption();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (!$model->save()) {
                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_DANGER,
                        'icon' => 'bi bi-x-circle-fill',
                        'message' => 'Could not create option',
                        'closeButton' => null,
                    ]);
                }
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "{$model->option_name} created successfully",
                    'closeButton' => null,
                ]);
            }
            

            return $this->redirect('index');
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgProgCurrOption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $option_id Option ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($option_id)
    {
        $model = $this->findModel($option_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->option_name . ' Updated!');
            return $this->redirect(['index']);
            // return $this->redirect(['view', 'option_id' => $model->option_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgCurrOption model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $option_id Option ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($option_id)
    {
        $this->findModel($option_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCurrOption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $option_id Option ID
     * @return OrgProgCurrOption the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($option_id)
    {
        if (($model = OrgProgCurrOption::findOne(['option_id' => $option_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
