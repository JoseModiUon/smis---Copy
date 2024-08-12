<?php

namespace app\modules\examManagement\controllers;

use app\modules\examManagement\models\ProgCurrLevelRequirement;
use app\modules\examManagement\models\search\ProgCurrLevelRequirementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * ProgCurrLevelReqController implements the CRUD actions for ProgCurrLevelRequirement model.
 */
class ProgCurrLevelReqController extends Controller
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
     * Lists all ProgCurrLevelRequirement models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProgCurrLevelRequirementSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProgCurrLevelRequirement model.
     * @param int $prog_curr_level_req_id Prog Curr Level Req ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curr_level_req_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curr_level_req_id),
        ]);
    }

    /**
     * Creates a new ProgCurrLevelRequirement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new ProgCurrLevelRequirement();

        if ($this->request->isPost) {
            //dd($_POST);
            if ($model->load($this->request->post())) {
                if ($model->save()) {
                    $data = $this->request->post('ProgCurrLevelRequirement');
                    return $this->redirect(['/exam-management/programmes/programme-requirements',
                        'prog_curriculum_id' => $data['prog_curriculum_id'],
                        'prog_code' => $data['prog_code']]);
                }
                dd($model->getErrors());
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProgCurrLevelRequirement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curr_level_req_id Prog Curr Level Req ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curr_level_req_id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($prog_curr_level_req_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'prog_curr_level_req_id' => $model->prog_curr_level_req_id]);
            $data = $this->request->post('ProgCurrLevelRequirement');
            return $this->redirect(['/exam-management/programmes/programme-requirements',
                'prog_curriculum_id' => $data['prog_curriculum_id'],
                'prog_code' => $data['prog_code']
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProgCurrLevelRequirement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curr_level_req_id Prog Curr Level Req ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curr_level_req_id)
    {
        $this->findModel($prog_curr_level_req_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProgCurrLevelRequirement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curr_level_req_id Prog Curr Level Req ID
     * @return ProgCurrLevelRequirement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curr_level_req_id)
    {
        if (($model = ProgCurrLevelRequirement::findOne(['prog_curr_level_req_id' => $prog_curr_level_req_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
