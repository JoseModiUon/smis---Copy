<?php

namespace app\modules\examManagement\controllers;

use app\modules\examManagement\models\ProgCurrGroupRequirement;
use app\modules\examManagement\models\search\ProgCurrGroupRequirementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgCurrGroupReqController implements the CRUD actions for ProgCurrGroupRequirement model.
 */
class ProgCurrGroupReqController extends Controller
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
     * Lists all ProgCurrGroupRequirement models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProgCurrGroupRequirementSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProgCurrGroupRequirement model.
     * @param int $prog_curr_group_requirement_id Prog Curr Group Requirement ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curr_group_requirement_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curr_group_requirement_id),
        ]);
    }

    /**
     * Creates a new ProgCurrGroupRequirement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProgCurrGroupRequirement();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                //                return $this->redirect(['index', 'prog_curr_group_requirement_id' => $model->prog_curr_group_requirement_id]);

                return $this->redirect(['/exam-management/programmes/programme-requirements',
                    'prog_curriculum_id' => $_REQUEST['prog_curriculum_id'],
                    'prog_code' => $_REQUEST['prog_code']]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProgCurrGroupRequirement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curr_group_requirement_id Prog Curr Group Requirement ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curr_group_requirement_id)
    {
        $model = $this->findModel($prog_curr_group_requirement_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'prog_curr_group_requirement_id' => $model->prog_curr_group_requirement_id]);
            //$data = $this->request->post('ProgCurrGroupReq');
            //dd($data);
            return $this->redirect(['/exam-management/programmes/programme-requirements',
                'prog_curriculum_id' => $_REQUEST['prog_curriculum_id'],
                'prog_code' => $_REQUEST['prog_code']]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProgCurrGroupRequirement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curr_group_requirement_id Prog Curr Group Requirement ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curr_group_requirement_id)
    {
        $this->findModel($prog_curr_group_requirement_id)->delete();

        return $this->redirect(['/exam-management/programmes/programme-requirements',
            'prog_curriculum_id' => $_REQUEST['prog_curriculum_id'],
            'prog_code' => $_REQUEST['prog_code']]);
        //return $this->redirect(['index']);
    }

    /**
     * Finds the ProgCurrGroupRequirement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curr_group_requirement_id Prog Curr Group Requirement ID
     * @return ProgCurrGroupRequirement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curr_group_requirement_id)
    {
        if (($model = ProgCurrGroupRequirement::findOne(['prog_curr_group_requirement_id' => $prog_curr_group_requirement_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
