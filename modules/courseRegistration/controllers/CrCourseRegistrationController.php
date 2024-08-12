<?php
/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-02-06 14:08:13 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-02-06 14:10:24
 * @Description: file:///home/wahomez/Github/smis/modules/courseRegistration/controllers/CrCourseRegistrationController.php
 */


namespace app\modules\courseRegistration\controllers;

use app\models\CrCourseRegistration;
use app\models\search\CrStudentCourseRegistrationSearch;
use Throwable;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * CrCourseRegistrationController implements the CRUD actions for CrCourseRegistration model.
 */
class CrCourseRegistrationController extends Controller
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

    /**Queries Search Model for course details of a specific student
     * Uses the registration number user in puts in view form
     * If no registration number is sent, $regNumber is set to null hence showing 'No records'
     */
    
    public function actionCourses()
    {
        try {
            $searchModel = new CrStudentCourseRegistrationSearch();

            $get = Yii::$app->request->get();

            if (!empty($get)) {
                $regNumber = Yii::$app->request->get('CrStudentCourseRegistrationSearch')['registration_number'];
            }
            // Get the submitted value

            if (empty($regNumber)) {
                // If $regNumber is empty, set it to null
                $regNumber = null;
            }

            $dataProvider = $searchModel->search($this->request->queryParams, $regNumber);


            return $this->render('studentCourses', [
                'title' => 'Student courses',
                'coursesSearchModel' => $searchModel,
                'coursesProvider' => $dataProvider,
                'regNumber' => $regNumber, // Pass the $regNumber to the view
            ]);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }




    /**
     * Displays a single CrCourseRegistration model.
     * @param int $student_course_reg_id Student Course Reg ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($student_course_reg_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($student_course_reg_id),
        ]);
    }

    /**
     * Creates a new CrCourseRegistration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CrCourseRegistration();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'student_course_reg_id' => $model->student_course_reg_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CrCourseRegistration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $student_course_reg_id Student Course Reg ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($student_course_reg_id)
    {
        $model = $this->findModel($student_course_reg_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'student_course_reg_id' => $model->student_course_reg_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CrCourseRegistration model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $student_course_reg_id Student Course Reg ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($student_course_reg_id)
    {
        $this->findModel($student_course_reg_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CrCourseRegistration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $student_course_reg_id Student Course Reg ID
     * @return CrCourseRegistration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($student_course_reg_id)
    {
        if (($model = CrCourseRegistration::findOne(['student_course_reg_id' => $student_course_reg_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
