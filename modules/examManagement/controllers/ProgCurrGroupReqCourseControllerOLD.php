<?php

namespace app\modules\examinationManagement\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\ProgCurrGroupReqCourse;
use app\models\search\ProgCurrGroupReqCourseSearch;

/**
 * ProgCurrGroupReqCourseController implements the CRUD actions for ProgCurrGroupReqCourse model.
 */
class ProgCurrGroupReqCourseController extends Controller
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
     * Displays a single ProgCurrGroupReqCourse model.
     * @param int $prog_curr_group_req_course_id Prog Curr Level Req ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curr_group_req_course_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curr_group_req_course_id),
        ]);
    }

    /**
     * Creates a new ProgCurrGroupReqCourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProgCurrGroupReqCourse();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', " Prog Curr Group Req Course Created!");
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
        try {
            $post = \Yii::$app->request->post();

            $ProgCurrGroupReqCourse = new ProgCurrGroupReqCourse();
            $ProgCurrGroupReqCourse->course_group_name = $post['group-req-id'];
            $ProgCurrGroupReqCourse->course_group_desc = $post['course-id'];
            $ProgCurrGroupReqCourse->course_group_type = $post['credit-factor'];


            if(!$ProgCurrGroupReqCourse->save()) {
                if(!$ProgCurrGroupReqCourse->validate()) {
                    throw new \Exception($this->getModelErrors($ProgCurrGroupReqCourse->getErrors()));
                } else {
                    throw new \Exception('Prog Curr Group Req Course not saved.');
                }
            }
            $transaction->commit();
            dd('success');
            //Redirect to list page
        } catch(\Exception $ex) {
            $transaction->rollback();
            dd($ex->getMessage());
            // redirect error page
        }
    }

    private function getModelErrors(array $modelErrors): string
    {
        $errorMsg = '';
        foreach ($modelErrors as $attributeErrors) {
            for($i = 0; $i < count($attributeErrors); $i++) {
                $errorMsg .= ' ' . $attributeErrors[$i];
            }
        }
        return $errorMsg;
    }


    /**
     * Finds the ProgCurrGroupReqCourse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curr_group_req_course_id Course Prerequisite ID
     * @return ProgCurrGroupReqCourse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curr_group_req_course_id)
    {
        if (($model = ProgCurrGroupReqCourse::findOne(['prog_curr_group_req_course_id' => $prog_curr_group_req_course_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
