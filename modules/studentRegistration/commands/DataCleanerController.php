<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 6/13/2024
 * @time: 1:08 PM
 */

namespace app\modules\studentRegistration\commands;

use app\helpers\SmisHelper;
use app\models\ExStudentCourses;
use app\modules\studentRegistration\models\AcademicLevel;
use app\modules\studentRegistration\models\AcademicProgress;
use app\modules\studentRegistration\models\AcademicProgressStatus;
use app\modules\studentRegistration\models\SPAcademicProgress;
use app\modules\studentRegistration\models\SPStudentSemesterSessionProgress;
use app\modules\studentRegistration\models\StudentProgCurriculum;
use app\modules\studentRegistration\models\StudentSemesterSessionProgress;
use app\modules\studentRegistration\models\StudentSemesterSessionStatus;
use app\modules\studentRegistration\models\StudentStatus;
use Yii;
use yii\db\Exception;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

class DataCleanerController extends BaseController
{
    /**
     * @throws Exception
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     */
    public function actionKma()
    {
        /**
         *  Already populated:
         *
         *  21/22 1.1 - 742 - 1
         *  21/22 1.2 - 753 - 2
         *  21/22 2.1 - 756 - 3
         *  22/23 2.2 - 703 - 4
         *  22/23 3.1 - 759 - 5
         *  22/23 3.2 - 760 - 6
         */
        $semesters = [
//            [
//                'id' => 761,
//                'progressNumber' => 7,
//                'level' => 4,
//                'sessionId' => 2
//            ],
//            [
//                'id' => 762,
//                'progressNumber' => 8,
//                'level' => 4,
//                'sessionId' => 2
//            ],
//            [
//                'id' => 763,
//                'progressNumber' => 9,
//                'level' => 4,
//                'sessionId' => 2
//            ]
        ];
        $semesters = [];

        echo ('Promoting students using the cli...') . PHP_EOL;

        $students = StudentProgCurriculum::find()
            ->select(['student_prog_curriculum_id', 'registration_number'])
            ->where(['like', 'registration_number', 'ND201%', false])
            ->andWhere(['like', 'registration_number', '%2023', false])
            ->asArray()->all();

        echo ("Found " . count($students) . ' students') . PHP_EOL;

        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();
        try {
            $academicProgressStatusId = AcademicProgressStatus::find()
                ->select(['progress_status_id'])
                ->where(['progress_status_name' => 'PROMOTED'])
                ->asArray()
                ->one()['progress_status_id'];

            $statusId = StudentStatus::find()->select(['status_id'])
                ->where(['status' => 'ACTIVE'])->asArray()->one()['status_id'];

            foreach ($semesters as $semester) {
                echo (
                        'Promoting following students into semester id ' . $semester['id'] . ' level ' . $semester['level']
                        . ' academic session id ' . $semester['sessionId']
                    ) . PHP_EOL;

                $sessionId = $semester['sessionId'];
                $progCurrSemId = $semester['id'];
                $semProgressNumber = $semester['progressNumber'];
                $academicLevelId = AcademicLevel::find()
                    ->select(['academic_level_id'])
                    ->where(['academic_level' => $semester['level']])
                    ->asArray()
                    ->one()['academic_level_id'];

                foreach ($students as $student) {
                    echo ($student['registration_number']) . PHP_EOL;
                    $studentProgCurrId = $student['student_prog_curriculum_id'];

                    $academicProgress = new AcademicProgress();
                    $academicProgress->acad_session_id = $sessionId;
                    $academicProgress->academic_level_id = $academicLevelId;
                    $academicProgress->student_prog_curriculum_id = $studentProgCurrId;
                    $academicProgress->progress_status_id = $academicProgressStatusId;
                    $academicProgress->current_status = $statusId;

                    if (!$academicProgress->save()) {
                        if (!$academicProgress->validate()) {
                            $errorMessage = SmisHelper::getModelErrors($academicProgress->getErrors());
                            throw new UnprocessableEntityHttpException($errorMessage);
                        } else {
                            throw new ServerErrorHttpException('Student academic progress was not saved.');
                        }
                    }

                    // Sync the registered student academic progress data with the student portal db
                    $spAcademicProgress = new SPAcademicProgress();
                    $spAcademicProgress->setAttributes($academicProgress->attributes);
                    if (!$spAcademicProgress->save()) {
                        if (!$spAcademicProgress->validate()) {
                            $errorMessage = SmisHelper::getModelErrors($spAcademicProgress->getErrors());
                            throw new UnprocessableEntityHttpException($errorMessage);
                        } else {
                            throw new ServerErrorHttpException('Student academic progress failed to sync.');
                        }
                    }


                    $studentSemesterSessionStatusId = StudentSemesterSessionStatus::find()->select(['status_id'])
                        ->where(['status_name' => 'ACTIVE'])->asArray()->one()['status_id'];

                    $studentSemSessionProgress = new StudentSemesterSessionProgress();
                    $studentSemSessionProgress->prog_curriculum_semester_id = $progCurrSemId;
                    $studentSemSessionProgress->registration_date = SmisHelper::formatDate('now', 'Y-m-d');
                    $studentSemSessionProgress->academic_progress_id = $academicProgress->academic_progress_id;
                    $studentSemSessionProgress->sem_progress_number = $semProgressNumber;
                    $studentSemSessionProgress->promotion_status = 'PROMOTED';
                    $studentSemSessionProgress->rep_status_id = $studentSemesterSessionStatusId;
                    $studentSemSessionProgress->prom_status_id = $studentSemesterSessionStatusId;

                    if (!$studentSemSessionProgress->save()) {
                        if (!$studentSemSessionProgress->validate()) {
                            $errorMessage = SmisHelper::getModelErrors($studentSemSessionProgress->getErrors());
                            throw new UnprocessableEntityHttpException($errorMessage);
                        } else {
                            throw new ServerErrorHttpException('Student semester session progress was not saved.');
                        }
                    }

                    // Sync the registered student semester session progress data with the student portal db
                    $spStudentSemSessionProgress = new SPStudentSemesterSessionProgress();
                    $spStudentSemSessionProgress->setAttributes($studentSemSessionProgress->attributes);
                    if (!$spStudentSemSessionProgress->save()) {
                        if (!$spStudentSemSessionProgress->validate()) {
                            $errorMessage = SmisHelper::getModelErrors($spStudentSemSessionProgress->getErrors());
                            throw new UnprocessableEntityHttpException($errorMessage);
                        } else {
                            throw new ServerErrorHttpException('Student semester session progress failed to sync.');
                        }
                    }
                }
            }
            $transaction->commit();
            $spTransaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function actionMarksheets()
    {
//        2022/2023_KM401_1_1_10_BMS100_4261 to 2021/2022_KM401_1_1_10_BMS100_4261
//2022/2023_KM401_1_1_10_BMS 101_4301 to 2021/2022_KM401_1_1_10_BMS 101_4301
//2022/2023_KM401_1_1_10_BMS 102_4321 to 2021/2022_KM401_1_1_10_BMS 102_4321
//2022/2023_KM401_1_1_10_BMS 103_4341 to 2021/2022_KM401_1_1_10_BMS 103_4341
//2022/2023_KM401_1_1_10_BMS 109_4361 to 2021/2022_KM401_1_1_10_BMS 109_4361
//
//2022/2023_KM401_1_2_10_BMS 105_4381 to 2021/2022_KM401_1_2_10_BMS 105_4381
//2022/2023_KM401_1_2_10_BMS 106_4401 to 2021/2022_KM401_1_2_10_BMS 106_4401
//2022/2023_KM401_1_2_10_BMS 107_4421 to 2021/2022_KM401_1_2_10_BMS 107_4421
//2022/2023_KM401_1_2_10_BMS 110_4441 to 2021/2022_KM401_1_2_10_BMS 110_4441
//2022/2023_KM401_1_2_10_BMS 112_4481 to 2021/2022_KM401_1_2_10_BMS 112_4481
//2022/2023_KM401_1_2_10_BMS 111_4461 to 2021/2022_KM401_1_2_10_BMS 111_4461
//
//2022/2023_KM401_2_3_10_BMS 108_4501 to 2021/2022_KM401_2_1_10_BMS 108_4501
//2022/2023_KM401_2_3_10_BMS 116_4561 to 2021/2022_KM401_2_1_10_BMS 116_4561
//2022/2023_KM401_2_3_10_BMS 404_4601 to 2021/2022_KM401_2_1_10_BMS 404_4601
//2022/2023_KM401_2_3_10_BMS 310_4581 to 2021/2022_KM401_2_1_10_BMS 310_4581
//2022/2023_KM401_2_3_10_BMS 113_4521 to 2021/2022_KM401_2_1_10_BMS 113_4521
//2022/2023_KM401_2_3_10_BMS 114_4541 to 2021/2022_KM401_2_1_10_BMS 114_4541
//2022/2023_KM401_2_3_10_BMS 115_5581 to 2021/2022_KM401_2_1_10_BMS 115_5581
//
//2023/2024_KM401_3_1_10_BMS 300_4761 to 2022/2023_KM401_3_1_10_BMS 300_4761
//2023/2024_KM401_3_1_10_BMS 304_4781 to 2022/2023_KM401_3_1_10_BMS 304_4781
//2023/2024_KM401_3_1_10_BMS 301_4801 to 2022/2023_KM401_3_1_10_BMS 301_4801
//2023/2024_KM401_3_1_10_BMS 302_4821 to 2022/2023_KM401_3_1_10_BMS 302_4821
//2023/2024_KM401_3_1_10_BMS 211_4841 to 2022/2023_KM401_3_1_10_BMS 211_4841
//2023/2024_KM401_3_1_10_BMS 309_4861 to 2022/2023_KM401_3_1_10_BMS 309_4861
//2023/2024_KM401_3_1_10_BMS 307_4881 to 2022/2023_KM401_3_1_10_BMS 307_4881
//2023/2024_KM401_3_1_10_BMS 308_4901 to 2022/2023_KM401_3_1_10_BMS 308_4901
//2023/2024_KM401_3_1_10_BMS 303_4921 to 2022/2023_KM401_3_1_10_BMS 303_4921
//2023/2024_KM401_2_1_10_BMS 415_4941 to 2022/2023_KM401_3_1_10_BMS 415_4941
        $tempIds = [
            '2023/2024_KM401_3_1_10_BMS 300_4761 to 2022/2023_KM401_3_1_10_BMS 300_4761',
            '2023/2024_KM401_3_1_10_BMS 304_4781 to 2022/2023_KM401_3_1_10_BMS 304_4781',
            '2023/2024_KM401_3_1_10_BMS 301_4801 to 2022/2023_KM401_3_1_10_BMS 301_4801',
            '2023/2024_KM401_3_1_10_BMS 302_4821 to 2022/2023_KM401_3_1_10_BMS 302_4821',
            '2023/2024_KM401_3_1_10_BMS 211_4841 to 2022/2023_KM401_3_1_10_BMS 211_4841',
            '2023/2024_KM401_3_1_10_BMS 309_4861 to 2022/2023_KM401_3_1_10_BMS 309_4861',
            '2023/2024_KM401_3_1_10_BMS 307_4881 to 2022/2023_KM401_3_1_10_BMS 307_4881',
            '2023/2024_KM401_3_1_10_BMS 308_4901 to 2022/2023_KM401_3_1_10_BMS 308_4901',
            '2023/2024_KM401_3_1_10_BMS 303_4921 to 2022/2023_KM401_3_1_10_BMS 303_4921',
            '2023/2024_KM401_2_1_10_BMS 415_4941 to 2022/2023_KM401_3_1_10_BMS 415_4941',
        ];

        $ids = [];
        foreach ($tempIds as $tempId){
            $parts = explode('to', $tempId);
            $ids[] = [
                'from' => trim($parts[0]),
                'to' => trim($parts[1])
            ];
        }

        foreach ($ids as $id) {
            echo 'Updating records from ' . $id['from'] . ' to ' . $id['to'] . PHP_EOL;

            $studentCourses = ExStudentCourses::find()->where(['mrksheet_id' => $id['from']])->all();
            echo ("Found " . count($studentCourses) . ' records') . PHP_EOL;

            $newMarksheetYear = explode('_', $id['to'])[0];

            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($studentCourses as $studentCourse) {
                    echo 'Updating ' . $studentCourse['course_registration_id'] . PHP_EOL;

                    $courseRegIdParts = explode('-', $studentCourse['course_registration_id']);
                    $newCourseRegId = $courseRegIdParts[0] . '-' . $newMarksheetYear . '-' . $courseRegIdParts[2];

                    $progressCodeParts = explode('-', $studentCourse['progress_code']);
                    $newProgressCode = $progressCodeParts[0] . '-' . $newMarksheetYear;

                    $studentCourse->course_registration_id = $newCourseRegId;
                    $studentCourse->progress_code = $newProgressCode;
                    $studentCourse->mrksheet_id = $id['to'];
                    $studentCourse->save();
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }

    public function actionSess()
    {
        $connection = \Yii::$app->db2;

        $studentsToPublishSql = "
        SELECT
            sm_academic_progress.academic_progress_id, 
            sm_academic_progress.academic_level_id, 
            sm_student_sem_session_progress.student_semester_session_id, 
            org_prog_curr_semester_group.programme_level, 
            org_academic_levels.academic_level, 
            org_academic_levels.academic_level_name, 
            org_prog_curr_semester_group.prog_curriculum_sem_group_id, 
            sm_student_sem_session_progress.prog_curriculum_semester_id, 
            sm_student_programme_curriculum.registration_number, 
            org_academic_session.acad_session_name
        FROM
            smisportal.sm_academic_progress
            INNER JOIN
            smisportal.sm_student_sem_session_progress
            ON 
                sm_academic_progress.academic_progress_id = sm_student_sem_session_progress.academic_progress_id
            INNER JOIN
            smisportal.org_prog_curr_semester_group
            ON 
                sm_student_sem_session_progress.prog_curriculum_semester_id = org_prog_curr_semester_group.prog_curriculum_semester_id
            INNER JOIN
            smisportal.org_academic_levels
            ON 
                sm_academic_progress.academic_level_id = org_academic_levels.academic_level_id
            INNER JOIN
            smisportal.sm_student_programme_curriculum
            ON 
                sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
            INNER JOIN
            smisportal.org_academic_session
            ON 
                sm_academic_progress.acad_session_id = org_academic_session.acad_session_id
        WHERE
            --sm_student_programme_curriculum.registration_number LIKE 'DC202%'
            org_prog_curr_semester_group.study_centre_group_id =221
        ORDER BY
            sm_academic_progress.academic_progress_id ASC
        ";

        $studentsToPublish = $connection->createCommand($studentsToPublishSql)->queryAll();

        foreach ($studentsToPublish as $student){
            $progress = SPStudentSemesterSessionProgress::findOne($student['student_semester_session_id']);
            $progress->prog_curriculum_semester_id = $student['prog_curriculum_sem_group_id'];
            $progress->save();
        }
    }
}