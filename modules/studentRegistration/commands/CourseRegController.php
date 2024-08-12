<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/11/2023
 * @time: 5:33 PM
 */

namespace app\modules\studentRegistration\commands;

use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\CourseRegistration;
use app\modules\studentRegistration\models\Marksheet;
use app\modules\studentRegistration\models\SPCourseRegistration;
use app\modules\studentRegistration\models\SPMarksheet;
use app\modules\studentRegistration\models\SPStudentSemesterSessionProgress;
use Exception;
use Yii;

class CourseRegController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function actionSync()
    {
        $transaction = Yii::$app->db->beginTransaction();
        SmisHelper::logMessage('Course reg sync started.', __METHOD__);
        try{
            $regCourses = SPCourseRegistration::find()->all();
            foreach ($regCourses as $regCourse){
                if(CourseRegistration::findOne($regCourse->student_course_reg_id)){
                    continue;
                }
                $reg = new CourseRegistration();
//                $reg->setAttributes($regCourse->attributes);
                $reg->student_course_reg_id = $regCourse->student_course_reg_id;
                $reg->timetable_id = $regCourse->timetable_id;
                $reg->student_semester_session_id = $regCourse->student_semester_session_id;
                $reg->course_registration_type_id = $regCourse->course_registration_type_id;
                $reg->registration_date = $regCourse->registration_date;
                $reg->course_reg_status_id = $regCourse->course_reg_status_id;
                $reg->userid = $regCourse->userid;
                $reg->class_code = $regCourse->class_code;
                $reg->registration_number = $regCourse->registration_number;
                if(!$reg->save()) {
                    if (!$reg->validate()) {
                        $errorMessage = SmisHelper::getModelErrors($reg->getErrors());
                        throw new Exception($errorMessage);
                    } else {
                        throw new Exception('Course registration failed to sync.');
                    }
                }
            }

            $regExams = SPMarksheet::find()->all();
            foreach ($regExams as $regExam){
                if(Marksheet::findOne($regExam->marksheet_id)){
                    continue;
                }
                $reg = new Marksheet();
                $reg->marksheet_id = $regExam->marksheet_id;
                $reg->student_course_reg_id = $regExam->student_course_reg_id;
//                $reg->setAttributes($regExam->attributes);
                if(!$reg->save()) {
                    if (!$reg->validate()) {
                        $errorMessage = SmisHelper::getModelErrors($reg->getErrors());
                        throw new Exception($errorMessage);
                    } else {
                        throw new Exception('Marksheet registration failed to sync.');
                    }
                }
            }
            $transaction->commit();
            SmisHelper::logMessage('Course reg sync complete.', __METHOD__);


        }catch (\Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            SmisHelper::logMessage($message, __METHOD__, 'error');
        }
    }
}
