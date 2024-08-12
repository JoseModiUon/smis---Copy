<?php

namespace app\modules\examProcessing\processor;

use app\helpers\SmisArrayHelper;
use app\models\ExStudentCourses;
use Yii;
use yii\db\ActiveRecord;
use yii\db\DataReader;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ExamProcessor
{

    private int $progDuration;
    private string $awardRounding;
    private string $averagingType;
    private int $academicYearId;
    private int $academicLevelId;
    private int $passMark;

    const PASS = 0;
    const FAIL = 0;

    /**
     * @param int $academicYearId
     * @param int $academicLevelId
     * @param int $progDuration
     * @param string $awardRounding
     * @param string $averagingType
     * @param int $passMark
     */
    public function __construct(int    $academicYearId, int $academicLevelId, int $progDuration,
                                string $awardRounding, string $averagingType, int $passMark)
    {
        $this->academicYearId = $academicYearId;
        $this->academicLevelId = $academicLevelId;
        $this->progDuration = $progDuration;
        $this->awardRounding = $awardRounding;
        $this->averagingType = $averagingType;
        $this->passMark = $passMark;
    }

    /**
     * @param string $regNo
     * @param array|ActiveRecord|null $studentOptionReq
     * @param int $minTotalCourses
     * @param int $maxTotalCourses
     * @param int $progCurriculumId
     * @param int $progDuration
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function processStudentRecommendation(
        string $regNo, array|ActiveRecord|null $studentOptionReq, int $minTotalCourses,
        int    $maxTotalCourses, int $progCurriculumId, int $progDuration): array
    {

        $studentCoursesList = [];
        $studentProcessedCourses = [];

        $totalGPAValue = 0;
        $totalGPAWeight = 0;

        $prevLevelPass = self::PASS;
        $totalFailedCourses = 0;
        $studentFailList = "";
        $totalMissedCourses = 0;
        $missingList = "";
        $recordIndex = 0;

        if ($recordIndex > -1) {
            $studentMarkSheets = $this->fetchStudentMarkSheets($regNo);

            //now process the mark sheets to evaluate the marks
            $filterList = SmisArrayHelper::extractField($studentMarkSheets, 'mark_sheet_id');

            if (count($filterList) > 0) {
                $studentCoursesMarked = $this->fetchMarkedStudentCourses(markSheetList: $filterList);
                foreach ($studentCoursesMarked as $key => $studentCourse) {
                    $courseCompoundId = SmisArrayHelper::getValue($studentCourse, 'course_id');
                    $courseArr = explode('_', $courseCompoundId);
                    $courseCode = SmisArrayHelper::getValue($courseArr, 0);
                    $courseId = intval(SmisArrayHelper::getValue($courseArr, 1));
                    $finalMark = SmisArrayHelper::getValue($studentCourse, 'final_mark');
                    $studentCoursesList[$courseId] = [
                        'COURSE_ID' => $courseId,
                        'COURSE_COMPOUND_ID' => $courseCompoundId,
                        'COURSE_CODE' => $courseCode,
                        'ENTRY_TYPE' => 'TAKEN',
                        'FINAL_MARK' => floatval($finalMark)
                    ];
                }

                $studentCoursesExempt = $this->fetchMarkedStudentCourses(markSheetList: $filterList, fetchExemptCourses: true);
                foreach ($studentCoursesExempt as $key => $studentCourse) {
                    $courseCompoundId = SmisArrayHelper::getValue($studentCourse, 'course_id');
                    $courseArr = explode('_', $courseCompoundId);
                    $courseCode = SmisArrayHelper::getValue($courseArr, 0);
                    $courseId = intval(SmisArrayHelper::getValue($courseArr, 1));
                    $studentCoursesList[$courseId] = [
                        'COURSE_ID' => $courseId,
                        'COURSE_COMPOUND_ID' => $courseCompoundId,
                        'COURSE_CODE' => $courseCode,
                        'ENTRY_TYPE' => 'EXEMPT',
                        'FINAL_MARK' => 0
                    ];
                }


            }


            echo Json::encode([
                'debug' => 'Processing student results ' . $regNo . ' academic year id ' . $this->academicYearId,
                'studentCourseList' => $studentCoursesList,
//                'filterList' => $filterList,
//                'results' => $studentMarkSheets
            ]);

            exit(0);
        }

        return [];

    }

    /**
     * @param $regNo
     * @return DataReader|array
     * @throws Exception
     */
    private function fetchStudentMarkSheets($regNo): DataReader|array
    {
        $query = <<<SQL
SELECT
	sm_academic_progress.acad_session_id,
	sm_academic_progress.academic_level_id,
	sm_student_programme_curriculum.registration_number,
	sm_student_programme_curriculum.prog_curriculum_id,
	org_academic_session.acad_session_name,
	sm_academic_progress.student_prog_curriculum_id,
	org_academic_session_semester.acad_session_semester_id,
	org_academic_session_semester.semester_code,
	cr_prog_curr_timetable.mrksheet_id as mark_sheet_id
FROM
	smis.sm_academic_progress
	INNER JOIN smis.sm_student_programme_curriculum ON sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN smis.org_academic_session ON sm_academic_progress.acad_session_id = org_academic_session.acad_session_id
	INNER JOIN smis.org_academic_session_semester ON org_academic_session.acad_session_id = org_academic_session_semester.acad_session_id
	INNER JOIN smis.org_prog_curr_semester ON org_academic_session_semester.acad_session_semester_id = org_prog_curr_semester.acad_session_semester_id
	INNER JOIN smis.org_prog_curr_semester_group ON org_prog_curr_semester.prog_curriculum_semester_id = org_prog_curr_semester_group.prog_curriculum_semester_id
	INNER JOIN smis.cr_prog_curr_timetable ON org_prog_curr_semester_group.prog_curriculum_sem_group_id = cr_prog_curr_timetable.prog_curriculum_sem_group_id
WHERE sm_student_programme_curriculum.registration_number = :regNo
AND sm_academic_progress.acad_session_id = :academicYearId
SQL;

        $params = [
            ':regNo' => $regNo,
            ':academicYearId' => $this->academicYearId
        ];


        if ($this->academicLevelId > 0) {
            $params[':academicLevelId'] = $this->academicLevelId;
            $query .= " AND sm_academic_progress.academic_level_id = :academicLevelId";
        }

        $query .= ' ORDER BY cr_prog_curr_timetable.mrksheet_id ASC';

        return Yii::$app->db->createCommand($query, $params)->queryAll();

    }


    /**
     * @param array $markSheetList
     * @param array $resultStatus
     * @param bool $fetchExemptCourses
     * @return array
     */
    private function fetchMarkedStudentCourses(
        array $markSheetList,
        array $resultStatus = ['PASS', 'TRANSCRIPT', null],
        bool  $fetchExemptCourses = false): array
    {
        $data = ExStudentCourses::find()
            ->andFilterWhere(['IN', 'mrksheet_id', $markSheetList])
            ->andFilterWhere(['IN', 'upper(result_status)', $resultStatus]);

        if ($fetchExemptCourses) {
            $data->andFilterWhere(['examtype_code' => 'EXEMPT']);
        } else {
            $data->andFilterWhere(['>', 'final_mark', 0]);
            $data->andFilterWhere(['!=', 'examtype_code', 'EXEMPT']);
        }

        return $data->asArray()->all();
    }
}
