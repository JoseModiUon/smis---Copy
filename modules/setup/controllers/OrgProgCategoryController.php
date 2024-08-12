<?php

namespace app\modules\setup\controllers;
use Yii;
use app\models\OrgProgCategory;
use app\models\search\OrgProgCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgProgCategoryController implements the CRUD actions for OrgProgCategory model.
 */
class OrgProgCategoryController extends Controller
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
     * Lists all OrgProgCategory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgCategory model.
     * @param int $prog_cat_id Prog Cat ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_cat_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_cat_id),
        ]);
    }

    /**
     * Creates a new OrgProgCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCategory();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', $model->prog_cat_name.' Created!');
                return $this->redirect(['index']);
//                return $this->redirect(['view', 'prog_cat_id' => $model->prog_cat_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgProgCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_cat_id Prog Cat ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_cat_id)
    {
        $model = $this->findModel($prog_cat_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->prog_cat_name.' Update!');
            return $this->redirect(['index']);
//            return $this->redirect(['view', 'prog_cat_id' => $model->prog_cat_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_cat_id Prog Cat ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_cat_id)
    {
        $this->findModel($prog_cat_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_cat_id Prog Cat ID
     * @return OrgProgCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_cat_id)
    {
        if (($model = OrgProgCategory::findOne(['prog_cat_id' => $prog_cat_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
