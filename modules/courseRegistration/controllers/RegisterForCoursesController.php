<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/16/2023
 * @time: 11:27 AM
 */
declare(strict_types=1);

namespace app\modules\courseRegistration\controllers;

use app\modules\courseRegistration\models\search\StudentsInSessionSearch;
use app\modules\courseRegistration\models\search\TimetablesSearch;
use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\CourseRegistration;
use app\modules\studentRegistration\models\Marksheet;
use app\modules\studentRegistration\models\ProgrammeCurriculumTimetable;
use app\modules\studentRegistration\models\SPCourseRegistration;
use app\modules\studentRegistration\models\SPCourseRegistrationStatus;
use app\modules\studentRegistration\models\SPCourseRegistrationType;
use app\modules\studentRegistration\models\SPMarksheet;
use app\modules\studentRegistration\models\StudentProgCurriculum;
use app\traits\ControllerTrait;
use app\traits\CourseFiltersTrait;
use Exception;
use Throwable;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class RegisterForCoursesController extends \yii\web\Controller
{
    private const TEACHING_TIMETABLE_FILTERS = 'teachingTimetableFilters';

    private const SUPP_TIMETABLE_FILTERS = 'suppTimetableFilters';

    use ControllerTrait;

    use CourseFiltersTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function actionTimetables(): string
    {
        try {
            $actionId = Yii::$app->request->get()['actionId'];
            $this->filterType = $this->getFilterType($actionId);

            $this->setActiveFilters(Yii::$app->request->get());

            $query = $this->queryCourses($actionId);

            return $this->render('timetables', [
                'title' => $this->createPageTitle('register for courses'),
                'searchModel' => $query['searchModel'],
                'dataProvider' => $query['dataProvider'],
                'years' => Yii::$app->params['academicYears'],
                'programs' => $this->getPrograms(),
                'programsCurr' => $this->getProgramCurr(),
                'academicLevels' => $this->getAcademicLevels(),
                'groups' => $this->getStudyGroups(),
                'semesters' => array_unique($this->getSemesters(), SORT_REGULAR),
                'activeFilters' => $this->activeFilters,
                'panelHeader' => $this->createPanelHeader(),
                'actionId' => $actionId
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @param string $actionId
     * @return Response
     */
    public function actionActiveFilters(string $actionId): Response
    {
        try {
            return $this->asJson([
                'success' => true,
                'courseFilters' => $this->getActiveFilters($this->getFilterType($actionId))
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @param string $marksheetId
     * @param $actionId
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionStudentsToRegister(string $marksheetId, $actionId): string
    {
        try {
            if (empty($marksheetId) || empty($actionId)) {
                throw new Exception('Invalid url parameters');
            }

            $this->activeFilters = $this->getActiveFilters($this->getFilterType($actionId));

            // Get the semester sessions for this marksheet
            $timetable = (new Query())
                ->select(['pct.timetable_id', 'pcsg.prog_curriculum_sem_group_id', 'pcsg.prog_curriculum_semester_id', 'pcs.acad_session_semester_id'])
                ->from(SMIS_DB_SCHEMA . '.cr_prog_curr_timetable pct')
                ->innerJoin(SMIS_DB_SCHEMA . '.org_prog_curr_semester_group pcsg', 'pcsg.prog_curriculum_sem_group_id=pct.prog_curriculum_sem_group_id')
                ->innerJoin(SMIS_DB_SCHEMA . '.org_prog_curr_semester pcs', 'pcs.prog_curriculum_semester_id=pcsg.prog_curriculum_semester_id')
                ->where(['pct.mrksheet_id' => $marksheetId])
                ->one();

            $studentsInSessionSearch = new StudentsInSessionSearch();
            $studentsInSessionProvider = $studentsInSessionSearch->search([
                'queryParams' => Yii::$app->request->queryParams,
                'progCurrSemGroupId' => $timetable['prog_curriculum_sem_group_id'],
                'progCurriculumSemesterId' => $timetable['prog_curriculum_semester_id'],
                'progCode' => $this->activeFilters['progCode']
            ]);

            return $this->render('studentsInSession', [
                'title' => $this->createPageTitle('students to register'),
                'studentsInSessionSearch' => $studentsInSessionSearch,
                'studentsInSessionProvider' => $studentsInSessionProvider,
                'marksheetId' => $marksheetId,
                'timetableId' => $timetable['timetable_id'],
                'progCurriculumSemesterId' => $timetable['prog_curriculum_semester_id'],
                'panelHeader' => $this->createPanelHeader(),
                'actionId' => $actionId,
                'filters' => $this->activeFilters
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Register students in courses
     * @return Response
     */
    public function actionAddToCourse(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $marksheetId = $post['marksheetId'];
            $students = $post['students'];

            $timetable = ProgrammeCurriculumTimetable::find()->select(['timetable_id', 'prog_curriculum_sem_group_id'])
                ->where(['mrksheet_id' => $marksheetId])->asArray()->one();
            $timetableId = $timetable['timetable_id'];

            $courseRegStatus = SPCourseRegistrationStatus::find()->where(['course_reg_status_name' => 'CONFIRMED'])
                ->asArray()->one();

            foreach ($students as $student) {
                $studentProg = StudentProgCurriculum::find()->select(['student_prog_curriculum_id'])
                    ->where(['registration_number' => $student['regNumber']])->asArray()->one();

                /**
                 * Only students in the semester session can be added to a course
                 */
                $sessionProgress = (new Query())
                    ->select(['sp.student_semester_session_id',])
                    ->from(SMIS_DB_SCHEMA . '.sm_student_sem_session_progress sp')
                    ->innerJoin(SMIS_DB_SCHEMA . '.sm_academic_progress ap', 'ap.academic_progress_id = sp.academic_progress_id')
                    ->innerJoin(SMIS_DB_SCHEMA . '.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = ap.student_prog_curriculum_id')
                    ->where([
                        'sp.prog_curriculum_semester_id' => $timetable['prog_curriculum_sem_group_id'],
                        'spc.student_prog_curriculum_id' => $studentProg['student_prog_curriculum_id']
                    ])->one();
                if (empty($sessionProgress)) continue;

                /**
                 * Prevent double registration
                 */
                $courseReg = SPCourseRegistration::find()->where([
                    'timetable_id' => $timetableId,
                    'student_semester_session_id' => $sessionProgress['student_semester_session_id']
                ])->one();
                if (!empty($courseReg)) continue;

                /**
                 * Attempt registration
                 */
                $courseRegType = SPCourseRegistrationType::find()->where(['course_reg_type_code' => $student['examType']])
                    ->asArray()->one();

                $courseReg = new SPCourseRegistration();
                $courseReg->timetable_id = $timetableId;
                $courseReg->student_semester_session_id = $sessionProgress['student_semester_session_id'];
                $courseReg->course_registration_type_id = $courseRegType['course_reg_type_id'];
                $courseReg->registration_date = SmisHelper::formatDate('now', 'Y-m-d');
                $courseReg->course_reg_status_id = $courseRegStatus['course_reg_status_id'];
                $courseReg->source_ipaddress = '';
                $courseReg->userid = Yii::$app->user->identity->username;
                $courseReg->registration_number = $student['regNumber'];
                $courseReg->sync_status = true;
                if (!$courseReg->save()) {
                    if (!$courseReg->validate()) {
                        throw new Exception(SmisHelper::getModelErrors($courseReg->getErrors()));
                    } else {
                        throw new Exception('Failed to register ' . $student['regNumber'] . ' in class marksheet: ' . $marksheetId);
                    }
                }

                $marksheet = new SPMarksheet();
                $marksheet->student_course_reg_id = $courseReg->student_course_reg_id;
                if (!$marksheet->save()) {
                    if (!$marksheet->validate()) {
                        throw new Exception(SmisHelper::getModelErrors($marksheet->getErrors()));
                    } else {
                        throw new Exception('Failed to register ' . $student['regNumber'] . ' in exam marksheet: ' . $marksheetId);
                    }
                }

                // Sync class marksheet registration to smis
                $syncCourseReg = new CourseRegistration();
                $syncCourseReg->student_course_reg_id = $courseReg->student_course_reg_id;
                $syncCourseReg->timetable_id = $courseReg->timetable_id;
                $syncCourseReg->student_semester_session_id = $courseReg->student_semester_session_id;
                $syncCourseReg->course_registration_type_id = $courseReg->course_registration_type_id;
                $syncCourseReg->registration_date = $courseReg->registration_date;
                $syncCourseReg->course_reg_status_id = $courseReg->course_reg_status_id;
                $syncCourseReg->userid = $courseReg->userid;
                $syncCourseReg->class_code = $courseReg->class_code;
                $syncCourseReg->registration_number = $courseReg->registration_number;
                if (!$syncCourseReg->save()) {
                    if (!$syncCourseReg->validate()) {
                        $errorMessage = SmisHelper::getModelErrors($syncCourseReg->getErrors());
                        throw new Exception($errorMessage);
                    } else {
                        throw new Exception('Failed sync registration of ' . $student['regNumber'] . ' in class marksheet: ' . $marksheetId);
                    }
                }

                // Sync exam marksheet registration to smis
                $syncMarksheet = new Marksheet();
                $syncMarksheet->marksheet_id = $marksheet->marksheet_id;
                $syncMarksheet->student_course_reg_id = $marksheet->student_course_reg_id;
                if (!$syncMarksheet->save()) {
                    if (!$syncMarksheet->validate()) {
                        $errorMessage = SmisHelper::getModelErrors($syncMarksheet->getErrors());
                        throw new Exception($errorMessage);
                    } else {
                        throw new Exception('Failed to sync registration- of ' . $student['regNumber'] . ' in exam marksheet: ' . $marksheetId);
                    }
                }
            }

            $transaction->commit();
            $spTransaction->commit();
            $this->setFlash('success', 'Course registration', 'Course registration done successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     * @throws Throwable
     */
    public function actionRemoveFromCourse(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $marksheetId = $post['marksheetId'];
            $students = $post['students'];

            $timetable = ProgrammeCurriculumTimetable::find()->select(['timetable_id'])
                ->where(['mrksheet_id' => $marksheetId])->asArray()->one();
            $timetableId = $timetable['timetable_id'];

            foreach ($students as $key => $regNumber) {
                $courseReg = CourseRegistration::find()->select(['student_course_reg_id'])->where([
                    'timetable_id' => $timetableId,
                    'registration_number' => $regNumber
                ])->one();

                $spCourseReg = SPCourseRegistration::find()->select(['student_course_reg_id'])->where([
                    'timetable_id' => $timetableId,
                    'registration_number' => $regNumber
                ])->one();

                // Only remove students who are registered in the course
                if (empty($spCourseReg) && empty($courseReg)) {
                    continue;
                }

                $courseRegId = $courseReg['student_course_reg_id'];
                $spCourseRegId = $spCourseReg['student_course_reg_id'];

                $marksheet = Marksheet::find()
                    ->select(['marksheet_id', 'course_work_mark', 'exam_mark', 'final_mark'])
                    ->where(['student_course_reg_id' => $courseRegId])
                    ->asArray()->one();

                $spMarksheet = SPMarksheet::find()
                    ->select(['marksheet_id', 'course_work_mark', 'exam_mark', 'final_mark'])
                    ->where(['student_course_reg_id' => $spCourseRegId])
                    ->asArray()->one();

                // Don't drop students who already have marks
                if (!empty($marksheet['course_work_mark']) ||
                    !empty($marksheet['exam_mark']) ||
                    !empty($marksheet['final_mark']) ||
                    !empty($spMarksheet['course_work_mark']) ||
                    !empty($spMarksheet['exam_mark']) ||
                    !empty($spMarksheet['final_mark'])
                ) {
                    continue;
                }

                if (!empty($marksheet)) {
                    Marksheet::findOne($marksheet['marksheet_id'])->delete();
                }

                if (!empty($spMarksheet)) {
                    SPMarksheet::findOne($spMarksheet['marksheet_id'])->delete();
                }

                if (!empty($courseReg)) {
                    CourseRegistration::findOne($courseRegId)->delete();
                }

                if (!empty($spCourseReg)) {
                    SPCourseRegistration::findOne($spCourseRegId)->delete();
                }
            }
            $transaction->commit();
            $spTransaction->commit();
            $this->setFlash('success', 'Course registration', 'Students removed from course successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionSelectedExamTypes(): Response
    {
        try {
            if (empty($students)) {
                return $this->asJson(['success' => true, 'regTypes' => []]);
            }

            $students = Yii::$app->request->get()['students'];
            $timetableId = Yii::$app->request->get()['timetableId'];
            $regTypes = [];
            foreach ($students as $key => $regNumber) {
                $courseReg = (new Query())
                    ->select(['crt.course_reg_type_code'])
                    ->from(SMIS_DB_SCHEMA . '.cr_course_registration cr')
                    ->innerJoin(SMIS_DB_SCHEMA . '.cr_course_reg_type crt', 'crt.course_reg_type_id = cr.course_registration_type_id')
                    ->where(['timetable_id' => $timetableId, 'registration_number' => $regNumber])
                    ->one();
                if (empty($courseReg)) {
                    continue;
                }
                $regTypes[] = ['regNumber' => $regNumber, 'code' => $courseReg['course_reg_type_code']];
            }

            return $this->asJson(['success' => true, 'regTypes' => $regTypes]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * Create different filter types for each action so as not overwrite other actions' filters when a page is opened in a new tab
     * @param string $actionId
     * @return string
     * @throws Exception
     */
    private function getFilterType(string $actionId): string
    {
        if ($actionId === 'supp') {
            $filters = self::SUPP_TIMETABLE_FILTERS;
        } elseif ($actionId === 'teaching') {
            $filters = self::TEACHING_TIMETABLE_FILTERS;
        } else {
            throw new Exception('Invalid url parameters');
        }
        return $filters;
    }

    /**
     * @param string $actionId
     * @return array
     */
    private function queryCourses(string $actionId): array
    {
        $filters = $this->activeFilters;

        $semesterType = 'TEACHING';
        if ($actionId === 'supp') {
            $semesterType = 'SUPPLEMENTARY';
        }

        $searchModel = new TimetablesSearch();
        $dataProvider = $searchModel->search([
            'queryParams' => Yii::$app->request->queryParams,
            'year' => $filters['year'],
            'progCode' => $filters['progCode'],
            'level' => $filters['level'],
            'semesterId' => $filters['semesterId'],
            'groupId' => $filters['groupId'],
            'semesterType' => $semesterType
        ]);

        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ];
    }
}