<?php

namespace app\modules\examManagement\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\ProgCurrCourseGroup;
use app\models\search\ProgCurrCourseGroupSearch;

/**
 * ProgCurrCourseGroupController implements the CRUD actions for ProgCurrCourseGroup model.
 */
class ProgCurrCourseGroupController extends Controller
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
     * Displays a single ProgCurrCourseGroup model.
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
     * Creates a new ProgCurrCourseGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProgCurrCourseGroup();

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
                
            $progCurrCourseGroup = new ProgCurrCourseGroup();
            $progCurrCourseGroup->course_group_name = $post['group-name'];
            $progCurrCourseGroup->course_group_desc = $post['group-desc'];
            $progCurrCourseGroup->course_group_type = $post['group-type'];


            if(!$progCurrCourseGroup->save()){
                if(!$progCurrCourseGroup->validate()){
                    throw new \Exception($this->getModelErrors($progCurrCourseGroup->getErrors()));
                }else{
                    throw new \Exception('Prog Curr Course Group not saved.');
                }
            }
            $transaction->commit();
           // dd('success');
//Redirect to list page
return $this->render('view', [
    'model' => $progCurrCourseGroup,
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
     * Finds the ProgCurrCourseGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $course_group_id Course Prerequisite ID
     * @return ProgCurrCourseGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($course_group_id)
    {
        if (($model = ProgCurrCourseGroup::findOne(['course_group_id' => $course_group_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
