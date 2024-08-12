<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 6/14/2024
 * @time: 2:51 PM
 */

namespace app\modules\courseRegistration\controllers;

use app\helpers\SmisHelper;
use app\models\OrgAcademicSession;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgProgrammes;
use app\models\search\CrProgCurrTimetableCreateSearchRefac;
use app\modules\courseRegistration\models\filters\TimetableFilter;
use app\modules\courseRegistration\models\search\CurrTimetablesSearch;
use app\modules\courseRegistration\models\search\ProgCurrCoursesSearch;
use app\modules\courseRegistration\models\SPProgrammeCurriculumTimetable;
use app\modules\studentRegistration\models\Course;
use app\modules\studentRegistration\models\ProgrammeCurriculumCourse;
use app\modules\studentRegistration\models\ProgrammeCurriculumTimetable;
use app\modules\studentRegistration\models\SemesterType;
use app\modules\studentRegistration\models\StudyCentre;
use app\modules\studentRegistration\models\StudyCentreGroup;
use app\modules\studentRegistration\models\StudyGroup;
use app\traits\ControllerTrait;
use Exception;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

class TimetablesController extends Controller
{
    use ControllerTrait;

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
     * Lists created timetables
     *
     * @return string
     */
    public function actionIndex(): string
    {
        // Filter values
        $academicSessId = null;
        $progCurrId = null;
        $semesterTypeId = null;
        $progLevelId = null;
        $semesterCode = null;
        $studyGroupId = null;
        $studyCenterId = null;

        $marksheetId = null;
        $semesterType = null;
        $panelHeader = null;

        if (array_key_exists('TimetableFilter', Yii::$app->request->get())) {
            $get = Yii::$app->request->get()['TimetableFilter'];
            $academicSessId = $get['acad_session_id'];
            $progCurrId = $get['prog_curriculum_id'];
            $semesterTypeId = $get['semester_type_id'];
            $progLevelId = $get['programme_level_id'];
            $semesterCode = $get['semester_code'];
            $studyGroupId = $get['study_group_id'];
            $studyCenterId = $get['study_centre_id'];

            $progCurr = OrgProgrammeCurriculum::findOne($progCurrId);
            $prog = OrgProgrammes::findOne($progCurr->prog_id);

            $semester = SemesterType::findOne($semesterTypeId);
            $semesterType = $semester->semester_type;

            $academicYear = OrgAcademicSession::findOne($academicSessId);
            $marksheetId = $academicYear->acad_session_name . '_' . $prog->prog_code . '_' .
                $progLevelId . '_' . $semesterCode . '_' . $studyGroupId;

            $panelHeader = $this->createPanelHeader($marksheetId, $studyGroupId, $studyCenterId);
        }

        $searchModel = new CurrTimetablesSearch();
        $dataProvider = $searchModel->search([
            'queryParams' => Yii::$app->request->queryParams,
            'partialMarksheetId' => $marksheetId,
            'semesterType' => $semesterType
        ]);

        // Save filters for retrieval on page redirects
        $session = Yii::$app->session;
        $session['TimetableFilters'] = [
            'progCurrId' => $progCurrId,
            'academicSessId' => $academicSessId,
            'studyCenterId' => $studyCenterId,
            'studyGroupId' => $studyGroupId,
            'semesterTypeId' => $semesterTypeId,
            'semesterCode' => $semesterCode,
            'progLevelId' => $progLevelId
        ];

        return $this->render('index', [
            'title' => $this->createPageTitle('List created timetables'),
            'filterModel' => $searchModel,
            'timetableFilter' => $this->setFiltersFromSession(),
            'dataProvider' => $dataProvider,
            'progCurrId' => $progCurrId,
            'level' => $progLevelId,
            'partialMarksheetId' => $marksheetId,
            'studyCenterId' => $studyCenterId,
            'studyGroupId' => $studyGroupId,
            'panelHeader' => $panelHeader
        ]);
    }

    /**
     * @return string
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate(): string
    {
        $get = Yii::$app->request->get();
        $progCurrId = $get['progCurrId'];
        $level = $get['level'];
        $partialMarksheetId = $get['partialMarksheetId'];
        $centerId = $get['centerId'];
        $groupId = $get['groupId'];

        if (empty($partialMarksheetId)) {
            throw new UnprocessableEntityHttpException("Please select proper filters");
        }

        $searchModel = new ProgCurrCoursesSearch();
        $dataProvider = $searchModel->search([
            'queryParams' => Yii::$app->request->queryParams,
            'progCurrId' => $progCurrId,
            'level' => $level
        ]);

        return $this->render('create', [
            'title' => $this->createPageTitle('Create timetables'),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'progCurrId' => $progCurrId,
            'level' => $level,
            'partialMarksheetId' => $partialMarksheetId,
            'studyCenterId' => $centerId,
            'studyGroupId' => $groupId,
            'filters' => $this->filtersToRedirectWith(),
            'panelHeader' => $this->createPanelHeader($partialMarksheetId, $groupId, $centerId)
        ]);
    }

    /**
     * @return Response
     */
    public function actionStore(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $progCoursesIds = $post['progCoursesIds'];
            $progCurrId = $post['progCurrId'];
            $level = $post['level'];
            $partialMarksheetId = $post['partialMarksheetId'];
            $studyCenterId = $post['studyCenterId'];
            $studyGroupId = $post['studyGroupId'];

            $studyCenterGroup = StudyCentreGroup::find()->where([
                'study_centre_id' => $studyCenterId,
                'study_group_id' => $studyGroupId
            ])->asArray()->one();

            $partialMarksheetDetails = explode('_', $partialMarksheetId);
            $academicYear = $partialMarksheetDetails[0];
            $semester = $partialMarksheetDetails[3];

            $semGroup = (new Query())
                ->select([
                    'psg.prog_curriculum_sem_group_id'
                ])
                ->from(SMIS_DB_SCHEMA . '.org_prog_curr_semester_group psg')
                ->innerJoin(SMIS_DB_SCHEMA . '.org_prog_curr_semester pcs', 'pcs.prog_curriculum_semester_id=psg.prog_curriculum_semester_id')
                ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session_semester ass', 'ass.acad_session_semester_id=pcs.acad_session_semester_id')
                ->innerJoin(SMIS_DB_SCHEMA . '.org_academic_session year', 'year.acad_session_id=ass.acad_session_id')
                ->where([
                    'pcs.prog_curriculum_id' => $progCurrId,
                    'psg.programme_level' => $level,
                    'ass.semester_code' => $semester,
                    'psg.study_centre_group_id' => $studyCenterGroup['study_centre_group_id'],
                    'year.acad_session_name' => $academicYear
                ])
                ->orderBy(['psg.prog_curriculum_sem_group_id' => SORT_ASC])
                ->one();

            if (empty($semGroup)) {
                throw new NotFoundHttpException('Semester not found. Please create one and submit again.');
            }

            foreach ($progCoursesIds as $progCoursesId) {
                $progCurrCourse = ProgrammeCurriculumCourse::find()->select('course_id')
                    ->where(['prog_curriculum_course_id' => $progCoursesId])->asArray()->one();

                $course = Course::find()->select(['course_id', 'course_code'])->where(['course_id' => $progCurrCourse['course_id']])
                    ->asArray()->one();

                $marksheetId = $partialMarksheetId . '_';
                $marksheetId .= $course['course_code'];
                $marksheetId .= '_';
                $marksheetId .= $course['course_id'];

                $timetableCount = ProgrammeCurriculumTimetable::find()
                    ->where([
                        'mrksheet_id' => $marksheetId,
                        'prog_curriculum_sem_group_id' => $semGroup['prog_curriculum_sem_group_id']
                    ])->count();

                if ((int)$timetableCount > 0) {
                    // don't create duplicate timetables
                    continue;
                }

                // Following fields will be updated later: exam date, venue and mode.
                $timetable = new ProgrammeCurriculumTimetable();
                $timetable->prog_curriculum_course_id = $progCoursesId;
                $timetable->prog_curriculum_sem_group_id = $semGroup['prog_curriculum_sem_group_id'];
                $timetable->mrksheet_id = $marksheetId;
                $timetable->exam_mode = 1; // default mode PHYSICAL

                if (!$timetable->save()) {
                    if (!$timetable->validate()) {
                        throw new Exception(SmisHelper::getModelErrors($timetable->getErrors()));
                    } else {
                        throw new Exception('Failed to create timetable for course ' . $course['course_code']);
                    }
                }

                // Sync the registered student academic progress data with the student portal db
                $spTimetable = new SPProgrammeCurriculumTimetable();
                $spTimetable->setAttributes($timetable->attributes);
                if (!$spTimetable->save()) {
                    if (!$spTimetable->validate()) {
                        $errorMessage = SmisHelper::getModelErrors($spTimetable->getErrors());
                        throw new UnprocessableEntityHttpException($errorMessage);
                    } else {
                        throw new ServerErrorHttpException('Timetable creation failed to sync.');
                    }
                }
            }

            $transaction->commit();
            $spTransaction->commit();
            $this->setFlash('success', 'Timetable creation', 'Courses timetables done successfully.');
            return $this->redirect(['/courseRegistration/timetables/index', 'TimetableFilter' => $this->filtersToRedirectWith()]);
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
     * Copy timetables to smis portal
     *
     * @return Response
     */
    public function actionPublish(): Response
    {
        $transaction = Yii::$app->db2->beginTransaction();
        try {
            $post = Yii::$app->request->post();

            foreach ($post['timetableIds'] as $timetableId) {
                $timetable = ProgrammeCurriculumTimetable::findOne($timetableId);

                $spTimetable = SPProgrammeCurriculumTimetable::findOne($timetable->timetable_id);
                // If timetable already published, just update it.
                // Else copy from smis to portal
                if (empty($spTimetable)) {
                    $spTimetable = new SPProgrammeCurriculumTimetable();
                }
                $spTimetable->setAttributes($timetable->attributes);
                if (!$spTimetable->save()) {
                    if (!$spTimetable->validate()) {
                        throw new Exception(SmisHelper::getModelErrors($spTimetable->getErrors()));
                    } else {
                        throw new Exception('Failed to publish timetable with marksheet id of ' . $timetable->mrksheet_id);
                    }
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Timetable creation', 'Courses timetables published to smis portal successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * Shows timetable edit form
     * @param string $marksheetId
     * @return string
     */
    public function actionEdit(string $marksheetId): string
    {
        return '';
    }

    /**
     * Update timetable
     * @return string
     */
    public function actionUpdate(): string
    {
        return '';
    }

    /**
     * Shows new schedule form
     * @param string $marksheetId
     * @return string
     */
    public function actionCreateSchedule(string $marksheetId): string
    {
        return '';
    }

    /**
     * Save new timetable schedule
     * @return string
     */
    public function actionStoreSchedule(): string
    {
        return '';
    }

    /**
     * @return TimetableFilter
     */
    private function setFiltersFromSession(): TimetableFilter
    {
        $session = Yii::$app->session;
        $timetableFilter = new TimetableFilter();
        $timetableFilter->prog_curriculum_id = $session['TimetableFilters']['progCurrId'];
        $timetableFilter->acad_session_id = $session['TimetableFilters']['academicSessId'];
        $timetableFilter->study_centre_id = $session['TimetableFilters']['studyCenterId'];
        $timetableFilter->study_group_id = $session['TimetableFilters']['studyGroupId'];
        $timetableFilter->semester_type_id = $session['TimetableFilters']['semesterTypeId'];
        $timetableFilter->semester_code = $session['TimetableFilters']['semesterCode'];
        $timetableFilter->programme_level_id = $session['TimetableFilters']['progLevelId'];
        return $timetableFilter;
    }

    /**
     * @return array
     */
    private function filtersToRedirectWith(): array
    {
        // These filters were gotten from the index page.
        // We want to redirect to index with the same filters that were there as before
        $timetableFilter = $this->setFiltersFromSession();
        return [
            "prog_curriculum_id" => $timetableFilter->prog_curriculum_id,
            "acad_session_id" => $timetableFilter->acad_session_id,
            "study_centre_id" => $timetableFilter->study_centre_id,
            "study_group_id" => $timetableFilter->study_group_id,
            "semester_type_id" => $timetableFilter->semester_type_id,
            "semester_code" => $timetableFilter->semester_code,
            "programme_level_id" => $timetableFilter->programme_level_id
        ];
    }

    /**
     * @param string $partialMarksheetId
     * @param string $groupId
     * @param string $centerId
     * @return string
     */
    private function createPanelHeader(string $partialMarksheetId, string $groupId, string $centerId): string
    {
        $marksheetIdDetails = explode('_', $partialMarksheetId);

        $panelHeader = $marksheetIdDetails[0] . ' - ';
        $panelHeader .= $marksheetIdDetails[1] . ' - LEVEL ';
        $panelHeader .= $marksheetIdDetails[2] . ' - SEMESTER ';
        $panelHeader .= $marksheetIdDetails[3] . ' - ';
        $group = StudyGroup::findOne($groupId);
        $panelHeader .= $group->study_group_name . ' - ';
        $center = StudyCentre::findOne($centerId);
        return $panelHeader .= strtoupper($center->study_centre_name);
    }
}