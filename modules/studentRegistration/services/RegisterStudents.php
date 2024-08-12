<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 1/16/2024
 * @time: 10:50 AM
 */

namespace app\modules\studentRegistration\services;

use app\helpers\SmisHelper;
use app\modules\studentRegistration\models\AcademicLevel;
use app\modules\studentRegistration\models\AcademicProgress;
use app\modules\studentRegistration\models\AcademicProgressStatus;
use app\modules\studentRegistration\models\AdmittedStudent;
use app\modules\studentRegistration\models\Cohort;
use app\modules\studentRegistration\models\Programme;
use app\modules\studentRegistration\models\ProgrammeCurriculum;
use app\modules\studentRegistration\models\SPAcademicProgress;
use app\modules\studentRegistration\models\SPStudent;
use app\modules\studentRegistration\models\SPStudentCohortHistory;
use app\modules\studentRegistration\models\SPStudentProgCurriculum;
use app\modules\studentRegistration\models\SPStudentSemesterSessionProgress;
use app\modules\studentRegistration\models\Student;
use app\modules\studentRegistration\models\StudentCohortHistory;
use app\modules\studentRegistration\models\StudentProgCurriculum;
use app\modules\studentRegistration\models\StudentSemesterSessionProgress;
use app\modules\studentRegistration\models\StudentSemesterSessionStatus;
use app\modules\studentRegistration\models\StudentStatus;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use yii\db\Query;
use yii\web\NotFoundHttpException;

final class RegisterStudents
{
    private const REGISTERED_STATUS = 'REGISTERED';
    private const ACADEMIC_PROGRESS_STATUS_REGISTERED = 'REGISTERED';
    private const ACADEMIC_PROGRESS_ACTIVE = 'ACTIVE';
    private const NEW_STUDENTS_ACADEMIC_LEVEL = 1;
    private const NEW_STUDENTS_SEMESTER_CODE = 1;
    private const FIRST_PROMOTION_PROGRESS_NUMBER = 1;  // A semester progress number counts how many times a student has been promoted.
    // Start it at 1 for new students
    private const STUDENT_SEMESTER_SESSION_STATUS_REPORTED = 'REPORTED';

    /**
     * @param AdmittedStudent $admittedStudent
     * @param string $regNumber
     * @param string|null $academicYear Admitted students come in at level 1 and semester 1. By default, we look for an active session semester that is running
     * during the day of admission. However, we also want to admit students who joined in the past years. In this case, we provide
     * the academic year too in which to look for the semester session.
     * @param int|null $globalSemester Semester that was active in the university calendar during which the students joined.
     * This doesn't mean that the student joins this semester.
     * @throws Exception
     */
    public function __construct(AdmittedStudent $admittedStudent,
                                string          $regNumber,
                                string          $academicYear = null,
                                int             $globalSemester = null)
    {
        $student = $this->createStudent($admittedStudent, $regNumber);

        $this->createStudentProgCurriculum($student, $admittedStudent);

        $this->createStudentCohortHistory($student);

        $sessionIds = $this->getAcademicAndSemesterSessionIds($student->student_number, $academicYear);

        $academicProgress = $this->createStudentAcademicProgress($student->student_number, $sessionIds['academicSessionId']);

        $this->createStudentSemesterSessionProgress($academicProgress, $sessionIds['progCurriculumSemGroupId'], $globalSemester);
    }

    /**
     * @param AdmittedStudent $admittedStudent
     * @param string $regNumber
     * @return Student
     * @throws Exception
     */
    private function createStudent(AdmittedStudent $admittedStudent, string $regNumber): Student
    {
        $student = new Student();
        $student->student_number = $regNumber;
        $student->surname = $admittedStudent->surname;
        $student->other_names = $admittedStudent->other_names;
        $student->gender = $admittedStudent->gender;
        $student->country_code = 'KEN';
        $student->id_no = $admittedStudent->national_id;
        $student->passport_no = $admittedStudent->passport_no;
        $student->service_number = $admittedStudent->service_number;
        $student->service = $admittedStudent->service;
        $student->blood_group = $admittedStudent->blood_group;
        $student->sponsor = $admittedStudent->sponsor;
        $student->registration_date = SmisHelper::formatDate('now', 'Y-m-d');
        $student->primary_phone_no = $admittedStudent->primary_phone_country_code . $admittedStudent->primary_phone_no;
        $student->alternative_phone_no = $admittedStudent->alternative_phone_country_code . $admittedStudent->alternative_phone_no;
        $student->primary_email = $admittedStudent->primary_email;
        $student->alternative_email = $admittedStudent->alternative_email;
        $student->post_code = $admittedStudent->post_code;
        $student->post_address = $admittedStudent->post_address;
        $student->town = $admittedStudent->town;
        $student->nationality = $admittedStudent->nationality;
        $student->date_of_birth = $admittedStudent->date_of_birth;

        if (!$student->save()) {
            if (!$student->validate()) {
                $errorMessage = SmisHelper::getModelErrors($student->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student was not saved.');
            }
        }

        // Sync the registered student data with the student portal db
        $spStudent = new SPStudent();
        $spStudent->setAttributes($student->attributes);
        if (!$spStudent->save()) {
            if (!$spStudent->validate()) {
                $errorMessage = SmisHelper::getModelErrors($spStudent->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student data failed to sync.');
            }
        }

        return $student;
    }

    /**
     * @param Student $student
     * @return void
     * @throws Exception
     */
    private function createStudentCohortHistory(Student $student): void
    {
        /**
         * It is possible to have more than one cohort open.
         * We place a student in the cohort that is still open. ie start_date <= current_date <= end_date
         * The student's cohort may be changed later.
         */
        $currentDate = SmisHelper::formatDate('now', 'Y-m-d');
        $cohort = Cohort::find()->select(['cohort_id'])->where(['cohort_status' => 'ACTIVE'])
            ->andWhere(['<=', 'adm_start_date', $currentDate])
            ->andWhere(['>=', 'adm_end_date', $currentDate])
            ->orderBy(['adm_end_date' => SORT_ASC])->asArray()->one();

        if (empty($cohort)) {
            throw new Exception('There is no available cohort to place a student in. Please create one and continue.');
        }

        $studentCohortHist = new StudentCohortHistory();
        $studentCohortHist->stud_id = $student->student_id;
        $studentCohortHist->cohort_id = $cohort['cohort_id'];
        $studentCohortHist->entry_date = $student->registration_date;
        $studentCohortHist->status = 'ACTIVE';
        $studentCohortHist->remark = self::REGISTERED_STATUS;
        if (!$studentCohortHist->save()) {
            if (!$studentCohortHist->validate()) {
                $errorMessage = SmisHelper::getModelErrors($studentCohortHist->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student cohort history was not saved.');
            }
        }

        // Sync the registered student cohort history data with the student portal db
        $spStudentCohortHist = new SPStudentCohortHistory();
        $spStudentCohortHist->setAttributes($studentCohortHist->attributes);
        if (!$spStudentCohortHist->save()) {
            if (!$spStudentCohortHist->validate()) {
                $errorMessage = SmisHelper::getModelErrors($spStudentCohortHist->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student data failed to sync.');
            }
        }
    }

    /**
     * @param Student $student
     * @param AdmittedStudent $admittedStudent
     * @return void
     * @throws Exception
     */
    private function createStudentProgCurriculum(Student $student, AdmittedStudent $admittedStudent): void
    {
        $studProgCurr = new StudentProgCurriculum();
        $studProgCurr->student_id = $student->student_id;
        $studProgCurr->registration_number = $student->student_number;
        /**
         * New programme curriculums can be created.
         * Therefore, get the most recently created that is active.
         */
        $programme = Programme::find()->select(['prog_id'])
            ->where(['prog_code' => $admittedStudent->uon_prog_code, 'status' => 'ACTIVE'])
            ->asArray()->one();

        if (empty($programme)) {
            throw new NotFoundHttpException('Programme ' . $admittedStudent->uon_prog_code . ' not found.');
        }

        $progCurr = ProgrammeCurriculum::find()->select(['prog_curriculum_id'])
            ->where(['prog_id' => $programme['prog_id'], 'status' => 'ACTIVE'])
            ->orderBy(['start_date' => SORT_DESC])->asArray()->one();
        $studProgCurr->prog_curriculum_id = $progCurr['prog_curriculum_id'];

        $studProgCurr->student_category_id = $admittedStudent->student_category_id;
        $studProgCurr->adm_refno = $admittedStudent->adm_refno;

        $studentStatus = StudentStatus::find()->select(['status_id'])->where(['status' => 'ACTIVE', 'current_status' => true])
            ->asArray()->one();
        $studProgCurr->status_id = $studentStatus['status_id'];

        if (!$studProgCurr->save()) {
            if (!$studProgCurr->validate()) {
                $errorMessage = SmisHelper::getModelErrors($studProgCurr->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student programme curriculum was not saved.');
            }
        }

        // Sync the registered student program curriculum data with the student portal db
        $spStudProgCurr = new SPStudentProgCurriculum();
        $spStudProgCurr->setAttributes($studProgCurr->attributes);
        if (!$spStudProgCurr->save()) {
            if (!$spStudProgCurr->validate()) {
                $errorMessage = SmisHelper::getModelErrors($spStudProgCurr->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student programme curriculum failed to sync.');
            }
        }
    }

    /**
     * @param string $regNumber
     * @param string $academicYear
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['academicSessionId' => "mixed", 'progCurriculumSemGroupId' => "mixed"])]
    private function getAcademicAndSemesterSessionIds(string $regNumber, string $academicYear): array
    {
        $studentProgCurr = StudentProgCurriculum::find()->select(['prog_curriculum_id', 'adm_refno'])
            ->where(['registration_number' => $regNumber])->asArray()->one();

        $admittedStudent = AdmittedStudent::find()->select('study_centre_group_id')
            ->where(['adm_refno' => $studentProgCurr['adm_refno']])->asArray()->one();

        $currentDate = SmisHelper::formatDate('now', 'Y-m-d');

        $query = (new Query())
            ->select([
                'psg.prog_curriculum_sem_group_id',
                'psg.prog_curriculum_semester_id',
                'ass.acad_session_id'
            ])
            ->from(SMIS_DB_SCHEMA . '.org_prog_curr_semester_group psg')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_prog_curr_semester pcs', 'pcs.prog_curriculum_semester_id=psg.prog_curriculum_semester_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session_semester ass', 'ass.acad_session_semester_id=pcs.acad_session_semester_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session year', 'year.acad_session_id=ass.acad_session_id')
            ->where([
                'psg.programme_level' => self::NEW_STUDENTS_ACADEMIC_LEVEL, // new students join in level 1
                'ass.semester_code' => self::NEW_STUDENTS_SEMESTER_CODE, // new students join in semester 1
                'psg.study_centre_group_id' => $admittedStudent['study_centre_group_id']
            ]);

        if (empty($academicYear)) {
            $query->andWhere(['<=', 'pcsg.start_date', $currentDate])
                ->andWhere(['>=', 'pcsg.end_date', $currentDate]);
        } else {
            $query->andWhere(['year.acad_session_name' => $academicYear]);
        }

        $semesterGroup = $query->one();

        if (empty($semesterGroup)) {
            $year = empty($academicYear) ? '' : $academicYear;
            throw new NotFoundHttpException(
                'Semester 1, Level 1 is missing in this year ' . $year . ' for the student ' .
                $regNumber . ' to be registered into. Create it to continue.');
        }

        return [
            'progCurriculumSemGroupId' => $semesterGroup['prog_curriculum_sem_group_id'],
            'academicSessionId' => $semesterGroup['acad_session_id']
        ];
    }

    /**
     * @param string $regNumber
     * @param string $academicSessionId
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['academicProgressId' => "mixed", 'academicSessionId' => "mixed", 'progCurrId' => "mixed"])]
    private function createStudentAcademicProgress(string $regNumber, string $academicSessionId): array
    {
        // New students come in at level 1
        $academicLevel = AcademicLevel::find()->select(['academic_level_id'])
            ->where(['academic_level' => self::NEW_STUDENTS_ACADEMIC_LEVEL])->asArray()->one();

        $academicProgressStatus = AcademicProgressStatus::find()->select(['progress_status_id'])
            ->where(['progress_status_name' => self::ACADEMIC_PROGRESS_STATUS_REGISTERED])->asArray()->one();

        $studentProgCurr = StudentProgCurriculum::find()->select(['student_prog_curriculum_id'])
            ->where(['registration_number' => $regNumber])->asArray()->one();

        $status = StudentStatus::find()->select(['status_id'])
            ->where(['status' => self::ACADEMIC_PROGRESS_ACTIVE])->asArray()->one();

        $academicProgress = new AcademicProgress();
        $academicProgress->acad_session_id = $academicSessionId;
        $academicProgress->academic_level_id = $academicLevel['academic_level_id'];
        $academicProgress->student_prog_curriculum_id = $studentProgCurr['student_prog_curriculum_id'];
        $academicProgress->progress_status_id = $academicProgressStatus['progress_status_id'];
        $academicProgress->current_status = $status['status_id'];

        if (!$academicProgress->save()) {
            if (!$academicProgress->validate()) {
                $errorMessage = SmisHelper::getModelErrors($academicProgress->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student academic progress was not saved.');
            }
        }

        // Sync the registered student academic progress data with the student portal db
        $spAcademicProgress = new SPAcademicProgress();
        $spAcademicProgress->setAttributes($academicProgress->attributes);
        if (!$spAcademicProgress->save()) {
            if (!$spAcademicProgress->validate()) {
                $errorMessage = SmisHelper::getModelErrors($spAcademicProgress->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student academic progress failed to sync.');
            }
        }

        return [
            'academicProgressId' => $academicProgress['academic_progress_id'],
            'academicSessionId' => $academicProgress['acad_session_id'],
            'progCurrId' => $studentProgCurr['student_prog_curriculum_id']
        ];
    }

    /**
     * @param array $academicProgress
     * @param string $progCurriculumSemGroupId
     * @param int $globalSemester
     * @return void
     * @throws \yii\db\Exception
     * @throws Exception
     */
    private function createStudentSemesterSessionProgress(array $academicProgress, string $progCurriculumSemGroupId, int $globalSemester): void
    {
        $studentSemesterSessionStatus = StudentSemesterSessionStatus::find()->select(['status_id'])
            ->where(['status_name' => self::STUDENT_SEMESTER_SESSION_STATUS_REPORTED])->asArray()->one();

        $studentSemSessionProgress = new StudentSemesterSessionProgress();
        $studentSemSessionProgress->prog_curriculum_semester_id = $progCurriculumSemGroupId;
        $studentSemSessionProgress->academic_progress_id = $academicProgress['academicProgressId'];
        $studentSemSessionProgress->sem_progress_number = self::FIRST_PROMOTION_PROGRESS_NUMBER;
        $studentSemSessionProgress->semester_progress = $globalSemester;
        $studentSemSessionProgress->rep_status_id = $studentSemesterSessionStatus['status_id'];
        $studentSemSessionProgress->prom_status_id = $studentSemesterSessionStatus['status_id'];
        $studentSemSessionProgress->promotion_status = 'REGISTERED';

        if (!$studentSemSessionProgress->save()) {
            if (!$studentSemSessionProgress->validate()) {
                $errorMessage = SmisHelper::getModelErrors($studentSemSessionProgress->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student semester session progress was not saved.');
            }
        }

        // Sync the registered student semester session progress data with the student portal db
        $spStudentSemSessionProgress = new SPStudentSemesterSessionProgress();
        $spStudentSemSessionProgress->setAttributes($studentSemSessionProgress->attributes);
        if (!$spStudentSemSessionProgress->save()) {
            if (!$spStudentSemSessionProgress->validate()) {
                $errorMessage = SmisHelper::getModelErrors($spStudentSemSessionProgress->getErrors());
                throw new Exception($errorMessage);
            } else {
                throw new Exception('Student semester session progress failed to sync.');
            }
        }
    }
}