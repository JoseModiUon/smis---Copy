<?php

namespace app\modules\examManagement\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\ProgCurrGroupRequirement;
use app\models\ProgCurrCourseGroup;
use app\models\search\ProgCurrGroupRequirementSearch;
use yii\web\Request;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;

/**
 * ProgCurrGroupRequirementController implements the CRUD actions for ProgCurrGroupRequirement model.
 */
class ProgCurrGroupRequirementController extends Controller
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


    public function actionIndex()
     {
        $id = $this->request->get('prog_curr_level_req_id');
        
        
        $innerDataProvider = ProgCurrGroupRequirement::find()
        ->select([
                'prog_curr_group_requirement.prog_curr_group_requirement_id',
                'prog_curr_group_requirement.prog_curr_level_req_id',
                'prog_curr_group_requirement.prog_curr_course_group_id',
                'prog_curr_group_requirement.min_group_courses',
                'prog_curr_group_requirement.group_pass_type',
                'prog_curr_group_requirement.min_group_pass',
                'prog_curr_group_requirement.min_group_pass',
                'prog_curr_course_group.course_group_name',
        ])
        ->innerJoinWith('courseGroupName')
        ->where([
            'prog_curr_group_requirement.prog_curr_level_req_id' => $id
            ])
        ->asArray()->all();

     
        //  if (!$yourModel) {
        //      throw new \yii\web\NotFoundHttpException('ProgCurrLevelRequirement not found.');
        //  }
        //  $relatedModels = $yourModel->relatedModels;
         
         $provider = new ArrayDataProvider([
            'allModels' => $innerDataProvider,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

         // Render the view or perform further operations with the related models
         return $this->render('index', [
             'searchModel' => $innerDataProvider,
             'dataProvider' => $provider,
         ]);
     }


    /**
     * Displays a single ProgCurrGroupRequirement model.
     * @param int $course_group_id Prog Curr Level Req ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($course_group_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($course_group_id),
        ]);
    }

    /**
     * Creates a new ProgCurrGroupRequirement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {

        $dropdown = ProgCurrCourseGroup::find()->all();

        return $this->render('create', [
            'dropdown' => $dropdown,
        ]);

        $model = new ProgCurrGroupRequirement();

        if ($this->request->isPost) {            
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', " Prog Curr Course Group Created!");
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    

    public function actionSave()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $post = \Yii::$app->request->post();
                
            $ProgCurrGroupRequirement = new ProgCurrGroupRequirement();
            $ProgCurrGroupRequirement->prog_curr_level_req_id = $post['prog-curr-level-req-id'];
            $ProgCurrGroupRequirement->prog_curr_course_group_id = $post['course-group'];
            $ProgCurrGroupRequirement->min_group_courses = $post['min-courses'];
            $ProgCurrGroupRequirement->group_pass_type = strtoupper($post['pass-type']);
            $ProgCurrGroupRequirement->min_group_pass = $post['group-pass'];
            $ProgCurrGroupRequirement->gpa_pass_type = strtoupper($post['gpa-pass']);
            $ProgCurrGroupRequirement->gpa_courses = $post['gpa-courses'];
            $ProgCurrGroupRequirement->extra_courses_processing = strtoupper($post['extra-courses']);


            if(!$ProgCurrGroupRequirement->save()){
                if(!$ProgCurrGroupRequirement->validate()){
                    throw new \Exception($this->getModelErrors($ProgCurrGroupRequirement->getErrors()));
                }else{
                    throw new \Exception('Prog Curriculum Group Requirement not saved.');
                }
            }
            $transaction->commit();
         //   dd('success');
//Redirect to list page
 // Render the view page with the record data
 return $this->render('view', [
    'model' => $ProgCurrGroupRequirement,
]);
        }catch(\Exception $ex){
            $transaction->rollback();
            dd($ex->getMessage());
            // redirect error page
        }
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
     * Finds the ProgCurrGroupRequirement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $course_group_id Course Prerequisite ID
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
