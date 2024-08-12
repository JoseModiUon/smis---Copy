<?php

namespace app\modules\setup\controllers;
use Yii;
use app\models\OrgStudyGroup;
use app\models\search\OrgStudyGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgStudyGroupController implements the CRUD actions for OrgStudyGroup model.
 */
class OrgStudyGroupController extends Controller
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
     * Lists all OrgStudyGroup models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgStudyGroupSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgStudyGroup model.
     * @param int $study_group_id Study Group ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($study_group_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($study_group_id),
        ]);
    }

    /**
     * Creates a new OrgStudyGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgStudyGroup();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
               // return $this->redirect(['view', 'study_group_id' => $model->study_group_id]);
                Yii::$app->getSession()->setFlash('success', $model->study_group_name.' Study Group Created!');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgStudyGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $study_group_id Study Group ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($study_group_id)
    {
        $model = $this->findModel($study_group_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->study_group_name.' Study Group Updated!');
           // return $this->redirect(['view', 'study_group_id' => $model->study_group_id]);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgStudyGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $study_group_id Study Group ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($study_group_id)
    {
        $this->findModel($study_group_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgStudyGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $study_group_id Study Group ID
     * @return OrgStudyGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($study_group_id)
    {
        if (($model = OrgStudyGroup::findOne(['study_group_id' => $study_group_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
