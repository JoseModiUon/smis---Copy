<?php

namespace app\modules\setup\controllers;
use Yii;
use app\models\OrgStudyCentre;
use app\models\search\OrgStudyCentreCodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgStudyCentreController implements the CRUD actions for OrgStudyCentre model.
 */
class OrgStudyCentreController extends Controller
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
     * Lists all OrgStudyCentre models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgStudyCentreCodeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgStudyCentre model.
     * @param int $study_centre_id Study Centre ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($study_centre_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($study_centre_id),
        ]);
    }

    /**
     * Creates a new OrgStudyCentre model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgStudyCentre();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
              //  return $this->redirect(['view', 'study_centre_id' => $model->study_centre_id]);
                Yii::$app->getSession()->setFlash('success', $model->study_centre_name.' Study Centre Created!');
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
     * Updates an existing OrgStudyCentre model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $study_centre_id Study Centre ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($study_centre_id)
    {
        $model = $this->findModel($study_centre_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->study_centre_name.' Study Centre Updated!');
          //return $this->redirect(['view', 'study_centre_id' => $model->study_centre_id]);
          return $this->redirect(['index', 'study_centre_id' => $model->study_centre_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgStudyCentre model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $study_centre_id Study Centre ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($study_centre_id)
    {
        $this->findModel($study_centre_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgStudyCentre model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $study_centre_id Study Centre ID
     * @return OrgStudyCentre the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($study_centre_id)
    {
        if (($model = OrgStudyCentre::findOne(['study_centre_id' => $study_centre_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
