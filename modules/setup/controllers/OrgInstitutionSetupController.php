<?php

namespace app\modules\setup\controllers;

use app\models\OrgInstitutionSetup;
use app\models\search\OrgInstitutionSetupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgInstitutionSetupController implements the CRUD actions for OrgInstitutionSetup model.
 */
class OrgInstitutionSetupController extends Controller
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
     * Lists all OrgInstitutionSetup models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgInstitutionSetupSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgInstitutionSetup model.
     * @param int $institution_setup_id Institution Setup ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($institution_setup_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($institution_setup_id),
        ]);
    }

    /**
     * Creates a new OrgInstitutionSetup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgInstitutionSetup();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'institution_setup_id' => $model->institution_setup_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgInstitutionSetup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $institution_setup_id Institution Setup ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($institution_setup_id)
    {
        $model = $this->findModel($institution_setup_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'institution_setup_id' => $model->institution_setup_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgInstitutionSetup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $institution_setup_id Institution Setup ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($institution_setup_id)
    {
        $this->findModel($institution_setup_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgInstitutionSetup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $institution_setup_id Institution Setup ID
     * @return OrgInstitutionSetup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($institution_setup_id)
    {
        if (($model = OrgInstitutionSetup::findOne(['institution_setup_id' => $institution_setup_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
