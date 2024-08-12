<?php

namespace app\modules\examManagement\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\ProgCurrLevelRequirement;
use app\models\ProgCurrGroupRequirement;
use app\models\OrgAcademicLevels;
use app\models\search\ProgCurrLevelRequirementSearch;
use yii\web\Request;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;

/**
 * ProgCurrLevelRequirementController implements the CRUD actions for ProgCurrLevelRequirement model.
 */
class ProgCurrLevelRequirementController extends Controller
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
     * Updates an existing ProgCurrLevelRequirement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_id Prog ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curr_level_req_id)
    
    {
        $id = $this->request->get('prog_curr_level_req_id');    
        $model = ProgCurrLevelRequirement::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('view', [
                'model' => $model   ,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
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

        // Fetch data from the database table
      //  $data = YourModel::find()->all();

      $dropdown = OrgAcademicLevels::find()->all();

        return $this->render('create', [
            'dropdown' => $dropdown,
        ]);

        $model = new ProgCurrLevelRequirement();

        if ($this->request->isPost) {            
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', " Prog Curr Level Req Created!");
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Display all ProgCurrLevelRequirement model for a specific course.
     * @return string|\yii\web\Response
     */

     public function actionIndex()
     {
        $id = $this->request->get('prog_curriculum_id');
        
        $level =  ProgCurrLevelRequirement::find('prog_study_level');
        
         $model = 
         ProgCurrLevelRequirement::find()
         ->select([
            'prog_curr_level_requirement.prog_curr_level_req_id',
            'org_programmes.prog_short_name',
            'prog_curr_level_requirement.prog_curriculum_id',
            'prog_curr_level_requirement.prog_study_level',
            'prog_curr_level_requirement.min_courses_taken',
            'prog_curr_level_requirement.pass_type',
            'prog_curr_level_requirement.min_pass_courses',
            'prog_curr_level_requirement.gpa_choice',
            'prog_curr_level_requirement.gpa_weight',
            'prog_curr_level_requirement.pass_result',
            'prog_curr_level_requirement.pass_recommendation',
            'prog_curr_level_requirement.fail_type',
            'prog_curr_level_requirement.fail_result',
            'prog_curr_level_requirement.fail_recommendation',
            'prog_curr_level_requirement.incomplete_result',
            'prog_curr_level_requirement.incomplete_recommendation',
            'org_academic_levels.academic_level_name',
         ])
         ->innerJoinWith(['programmeCurriculum' => function(ActiveQuery $pr){
            $pr->innerJoinWith(['prog']);
         }])
         ->where([
            'org_programme_curriculum.prog_curriculum_id' => $id])

        ->innerJoinWith('academicLevelName')
        ->asArray()->all();
         
         $provider = new ArrayDataProvider([
            'allModels' => $model,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

         // Render the view or perform further operations with the related models
         return $this->render('index', [
             'searchModel' => $model,
             'dataProvider' => $provider,
         ]);
     }
    

    public function actionSave()
    {
       // Yii::$app->cache->flush();
        $transaction = \Yii::$app->db->beginTransaction();
        $request = new Request();
        $id = $request->get('prog_curriculum_id');
        try{
            $post = \Yii::$app->request->post();
                
            $progCurrLevelReq = new ProgCurrLevelRequirement();
            $progCurrLevelReq->prog_curriculum_id = $post['prog-curr-id'];
            $progCurrLevelReq->min_courses_taken = $post['min-courses-taken'];
            $progCurrLevelReq->prog_study_level = $post['study-level'];
            $progCurrLevelReq->min_pass_courses = $post['min-courses-pass'];
            $progCurrLevelReq->pass_type = $post['pass-type'];
            $progCurrLevelReq->gpa_choice = $post['gpa-choices'];
            $progCurrLevelReq->gpa_courses = $post['gpa-num'];
            $progCurrLevelReq->gpa_weight = $post['gpa-weight'];
            $progCurrLevelReq->pass_result = strtoupper($post['pass-result']);
            $progCurrLevelReq->fail_result = strtoupper($post['fail-res']);
            $progCurrLevelReq->pass_recommendation = strtoupper($post['pass-recom']); 
            $progCurrLevelReq->fail_recommendation = strtoupper($post['fail-recom']); 
            $progCurrLevelReq->incomplete_result = strtoupper($post['incomplete-res']); 
            $progCurrLevelReq->incomplete_recommendation = strtoupper($post['incomplete-recom']); 


            //$id = $this->request->get('prog_curriculum_id');
        
            $duplicate = ProgCurrLevelRequirement::find()
            ->where(['prog_curriculum_id' => $progCurrLevelReq->prog_curriculum_id, 'prog_study_level' =>$progCurrLevelReq->prog_study_level]);

            $duplicates = $duplicate->count();

         if ($duplicates > 0) {
            // Duplicate study level found for the course
            throw new \Exception('Prog level requirement not saved. Duplicate study level found for the course');
         }
            if(!$progCurrLevelReq->save()){
                if(!$progCurrLevelReq->validate()){
                    throw new \Exception($this->getModelErrors($progCurrLevelReq->getErrors()));
                }
                else{
                    throw new \Exception('Prog level requirement not saved.');
                }
            }
        
        
            $transaction->commit();
           // dd('success');
            //Redirect to list page
            // Render the view page with the record data
            return $this->render('view', [
                'model' => $progCurrLevelReq,
            ]);
                    }catch(\Exception $ex){
                        $transaction->rollback();
                        dd($ex->getMessage());
                        // redirect error page
                    }
                }


    public function actionDelete($prog_curr_level_req_id)
    {
        $this->findModel($prog_curr_level_req_id)->delete();

        return $this->redirect(['index']);
    }           

    private function getModelErrors(array $modelErrors): string
    {
        $errorMsg = '';
        foreach ($modelErrors as $attributeErrors){
            for($i=0; $i < count($attributeErrors); $i++){
                $errorMsg .= ' ' . $attributeErrors[$i];
            }
        }
        return $errorMsg;
    }

    
    /**
     * Finds the ProgCurrLevelRequirement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curr_level_req_id Course Prerequisite ID
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
