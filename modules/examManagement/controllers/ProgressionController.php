<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/27/2024
 * @time: 10:17 AM
 */

namespace app\modules\examManagement\controllers;

use app\helpers\SmisHelper;
use app\modules\examManagement\models\search\StudentsToProgressSearch;
use app\modules\studentRegistration\models\AcademicLevel;
use app\modules\studentRegistration\models\AcademicProgress;
use app\modules\studentRegistration\models\AcademicProgressStatus;
use app\modules\studentRegistration\models\AcademicSession;
use app\modules\studentRegistration\models\ProgCurrSemesterGroup;
use app\modules\studentRegistration\models\ProgrammeCurriculum;
use app\modules\studentRegistration\models\SPAcademicProgress;
use app\modules\studentRegistration\models\SPStudentSemesterSessionProgress;
use app\modules\studentRegistration\models\StudentProgCurriculum;
use app\modules\studentRegistration\models\StudentSemesterSessionProgress;
use app\modules\studentRegistration\models\StudentSemesterSessionStatus;
use app\modules\studentRegistration\models\StudentStatus;
use app\modules\studentRegistration\models\StudyCentre;
use app\modules\studentRegistration\models\StudyCentreGroup;
use app\modules\studentRegistration\models\StudyGroup;
use app\traits\ControllerTrait;
use app\traits\CourseFiltersTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

final class ProgressionController extends \yii\web\Controller
{
    use CourseFiltersTrait;
    use ControllerTrait;

    private const PROGRESSION_FILTERS = 'progressionFilters';
    private const ACADEMIC_PROGRESS_ACTIVE = 'ACTIVE';
    private const ACADEMIC_PROGRESS_STATUS_REGISTERED = 'PROMOTED';
    private const STUDENT_SEMESTER_SESSION_STATUS_REPORTED = 'ACTIVE';

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
                    ]
                ],
            ]
        ];
    }

    /**
     * @return string
     * @throws UnprocessableEntityHttpException
     */
    public function actionIndex(): string
    {
        $this->filterType = self::PROGRESSION_FILTERS;
        $this->setActiveFilters(Yii::$app->request->get());
        $filters = $this->activeFilters;

        $searchModel = new StudentsToProgressSearch();
        $dataProvider = $searchModel->search([
            'queryParams' => Yii::$app->request->queryParams,
            'year' => $filters['year'],
            'progCode' => $filters['progCode'],
            'level' => $filters['level'],
            'semesterId' => $filters['semesterId'],
            'groupId' => $filters['groupId']
        ]);

        return $this->render('index', [
            'title' => $this->createPageTitle('progression filters'),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'years' => Yii::$app->params['academicYears'],
            'programs' => $this->getPrograms(),
            'programsCurr' => $this->getProgramCurr(),
            'academicLevels' => $this->getAcademicLevels(),
            'groups' => $this->getStudyGroups(),
            'semesters' => array_unique($this->getSemesters(), SORT_REGULAR),
            'activeFilters' => $this->activeFilters,
            'panelHeader' => $this->createPanelHeader(),
        ]);
    }

    /**
     * @return Response
     */
    public function actionActiveFilters(): Response
    {
        try {
            return $this->asJson([
                'success' => true,
                'courseFilters' => $this->getActiveFilters(self::PROGRESSION_FILTERS)
            ]);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function actionSaveProgress(): Response
    {
        $post = Yii::$app->request->post();
        $progCurrSemGroupId = $post['progCurrSemGroupId'];
        $students = $post['students'];

        $progCurrSemGroup = ProgCurrSemesterGroup::find()->alias('psg')
            ->select([
                'psg.prog_curriculum_sem_group_id',
                'psg.prog_curriculum_semester_id',
                'psg.study_centre_group_id'
            ])
            ->joinWith(['progCurrSemester ps' => function (ActiveQuery $q) {
                $q->select([
                    'ps.prog_curriculum_semester_id',
                    'ps.acad_session_semester_id',
                    'ps.prog_curriculum_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['progCurrSemester.academicSessionSemester ass' => function (ActiveQuery $q) {
                $q->select([
                    'ass.acad_session_semester_id',
                    'ass.semester_code',
                ]);
            }], true, 'INNER JOIN')
            ->where(['psg.prog_curriculum_sem_group_id' => $progCurrSemGroupId])
            ->asArray()->one();

        $currentSemester = $progCurrSemGroup['progCurrSemester']['academicSessionSemester']['semester_code'];
        $progCurrId = $progCurrSemGroup['progCurrSemester']['prog_curriculum_id'];
        $studyCenterGroupId = $progCurrSemGroup['study_centre_group_id'];

        $annualSemesters = ProgrammeCurriculum::find()->select(['annual_semesters'])
            ->where(['prog_curriculum_id' => $progCurrSemGroup['progCurrSemester']['prog_curriculum_id']])
            ->asArray()->one()['annual_semesters'];

        $session = Yii::$app->session;
        $filters = $session[self::PROGRESSION_FILTERS];

        // work with the assumption that the student has passed
        if ($currentSemester < $annualSemesters) {
            $nextSemester = $currentSemester + 1;
            $academicYear = $filters['year'];
            $level = $filters['level'];
        } elseif ($currentSemester > $annualSemesters) {
            throw new UnprocessableEntityHttpException(
                'Current semester of ' . $currentSemester . ' must not exceed the annual semesters ' . $annualSemesters . ' of the program.');
        } else {
            $nextSemester = 1;
            $academicYearSplits = explode('/', $filters['year']);
            $academicYear = (int)$academicYearSplits[0] + 1 . '/' . (int)$academicYearSplits[1] + 1;
            $level = $filters['level'] + 1;
        }

        $progCurrSemGroup = ProgCurrSemesterGroup::find()->alias('psg')
            ->select([
                'psg.prog_curriculum_sem_group_id',
                'psg.prog_curriculum_semester_id',
            ])
            ->joinWith(['progCurrSemester ps' => function (ActiveQuery $q) {
                $q->select([
                    'ps.prog_curriculum_semester_id',
                    'ps.acad_session_semester_id',
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['progCurrSemester.academicSessionSemester ass' => function (ActiveQuery $q) {
                $q->select([
                    'ass.acad_session_semester_id',
                    'ass.acad_session_id',
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['progCurrSemester.academicSessionSemester.academicSession acs' => function (ActiveQuery $q) {
                $q->select([
                    'acs.acad_session_id',
                ]);
            }], true, 'INNER JOIN')
            ->where([
                'psg.programme_level' => $level,
                'psg.study_centre_group_id' => $studyCenterGroupId,
                'ps.prog_curriculum_id' => $progCurrId,
                'ass.semester_code' => $nextSemester,
                'acs.acad_session_name' => $academicYear,
            ])
            ->asArray()->one();

        if (empty($progCurrSemGroup)) {
            $studyCenterGroup = StudyCentreGroup::findOne($studyCenterGroupId);
            $studyGroup = StudyGroup::findOne($studyCenterGroup->study_group_id);
            $studyCenter = StudyCentre::findOne($studyCenterGroup->study_centre_id);
            throw new NotFoundHttpException(
                'The next semester is missing. Create one with following details: Semester: ' . $nextSemester .
                ', Level: ' . $level . ', Year: ' . $academicYear . ', Program code ' . $filters['progCode'] .
                ', Group: ' . $studyGroup->study_group_name . ', Study center: ' . $studyCenter->study_centre_name
            );
        }

        $nextProgCurrSemGroupId = $progCurrSemGroup['prog_curriculum_sem_group_id'];

        // Attempt to progress students
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();
        try {
            $academicSessionId = AcademicSession::find()->select(['acad_session_id'])->where(['acad_session_name' => $academicYear])
                ->asArray()->one()['acad_session_id'];

            $academicLevelId = AcademicLevel::find()->select(['academic_level_id'])
                ->where(['academic_level' => $level])->asArray()->one()['academic_level_id'];

            $academicProgressStatusId = AcademicProgressStatus::find()->select(['progress_status_id'])
                ->where(['progress_status_name' => self::ACADEMIC_PROGRESS_STATUS_REGISTERED])
                ->asArray()->one()['progress_status_id'];

            $statusId = StudentStatus::find()->select(['status_id'])
                ->where(['status' => self::ACADEMIC_PROGRESS_ACTIVE])->asArray()->one()['status_id'];

            foreach ($students as $key => $regNumber) {
                $studentProgCurrId = StudentProgCurriculum::find()->select(['student_prog_curriculum_id'])
                    ->where(['registration_number' => $regNumber])->asArray()->one()['student_prog_curriculum_id'];

                /**
                 * check if student exists in the semester we are trying to move them into and don't progress
                 */
                $semSessProgress = (new Query())->select('ssp.student_semester_session_id')
                    ->from('smis.sm_academic_progress ap')
                    ->innerJoin('smis.org_academic_session acad', 'acad.acad_session_id=ap.acad_session_id')
                    ->innerJoin('smis.sm_student_sem_session_progress ssp', 'ssp.academic_progress_id=ap.academic_progress_id')
                    ->where([
                        'ap.student_prog_curriculum_id' => $studentProgCurrId,
                        'acad.acad_session_name' => $academicYear,
                        'ssp.prog_curriculum_semester_id' => $nextProgCurrSemGroupId
                    ])->one();

                if (!empty($semSessProgress)) {
                    continue;
                }

                /**
                 * Go ahead and attempt the promotion
                 */
                $academicProgressCount = AcademicProgress::find()->where(['student_prog_curriculum_id' => $studentProgCurrId])
                    ->count();
                $semProgressNumber = (int)$academicProgressCount + 1;

                $academicProgress = new AcademicProgress();
                $academicProgress->acad_session_id = $academicSessionId;
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

                $studentSemesterSessionStatusId = StudentSemesterSessionStatus::find()->select(['status_id'])
                    ->where(['status_name' => self::STUDENT_SEMESTER_SESSION_STATUS_REPORTED])->asArray()->one()['status_id'];

                $studentSemSessionProgress = new StudentSemesterSessionProgress();
                $studentSemSessionProgress->prog_curriculum_semester_id = $nextProgCurrSemGroupId;
                $studentSemSessionProgress->academic_progress_id = $academicProgress->academic_progress_id;
                $studentSemSessionProgress->sem_progress_number = $semProgressNumber;
                $studentSemSessionProgress->promotion_status = 'PROMOTED';
                $studentSemSessionProgress->rep_status_id = $studentSemesterSessionStatusId;
                $studentSemSessionProgress->prom_status_id = $studentSemesterSessionStatusId;
                $studentSemSessionProgress->promotion_status = 'PROMOTED';

                if (!$studentSemSessionProgress->save()) {
                    if (!$studentSemSessionProgress->validate()) {
                        $errorMessage = SmisHelper::getModelErrors($studentSemSessionProgress->getErrors());
                        throw new UnprocessableEntityHttpException($errorMessage);
                    } else {
                        throw new ServerErrorHttpException('Student semester session progress was not saved.');
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
            $transaction->commit();
            $spTransaction->commit();
        } catch (\Exception $ex) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            throw $ex;
        }
        $this->setFlash('success', 'progress students', 'Students promoted to the next semester successfully.');
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}