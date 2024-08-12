<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 7/25/2023
 * @time: 10:42 AM
 */
declare(strict_types=1);

namespace app\traits;

use app\modules\studentRegistration\models\AcademicLevel;
use app\modules\studentRegistration\models\AcademicSessionSemester;
use app\modules\studentRegistration\models\ProgCurrSemesterGroup;
use app\modules\studentRegistration\models\Programme;
use app\modules\studentRegistration\models\ProgrammeCurriculum;
use app\modules\studentRegistration\models\StudyGroup;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

trait CourseFiltersTrait
{
    private string $filterType;

    private array $activeFilters;

    /**
     * Get filters from request and set in the courses query
     * Store filters in session to retrieve them again on page reloads
     * @param array $get
     * @return void
     */
    private function setActiveFilters(array $get): void
    {
        // Default the year to the current academic year
        $year = (array_key_exists('year', $get)) ? $get['year'] : Yii::$app->params['currentAcademicYear'];
        $progCode = (array_key_exists('program', $get)) ? $get['program'] : '';
        $level = (array_key_exists('level', $get)) ? $get['level'] : '';
        $semesterId = (array_key_exists('semester-id', $get)) ? $get['semester-id'] : '';
        $groupId = (array_key_exists('group-id', $get)) ? $get['group-id'] : '';

        $session = Yii::$app->session;
        $session[$this->filterType] = [
            'year' => $year,
            'progCode' => $progCode,
            'level' => $level,
            'semesterId' => $semesterId,
            'groupId' => $groupId
        ];

        $this->activeFilters = $session[$this->filterType];
    }

    /**
     * @param string $filterType
     * @return array|null
     */
    private function getActiveFilters(string $filterType): array|null
    {
        $session = Yii::$app->session;
        return $session[$filterType];
    }

    /**
     * @return string
     */
    private function createPanelHeader(): string
    {
        $activeFilters = $this->activeFilters;

        $panelHeader = $activeFilters['year'];

        if (!empty($activeFilters['progCode'])) {
            $panelHeader .= ' - ' . $activeFilters['progCode'];
        }

        if (!empty($activeFilters['level'])) {
            $panelHeader .= ' - Year ' . $activeFilters['level'];
        }

//        if(!empty($activeFilters['semesterId'])){
//            $semester = AcademicSessionSemester::find()->select(['semester_code', 'acad_session_semester_desc'])
//                ->where(['acad_session_semester_id' => $activeFilters['semesterId']])->asArray()->one();
//            $panelHeader .= ' - Semester ' . $semester['semester_code'] . ' ' .$semester['acad_session_semester_desc'];
//        }

        if (!empty($activeFilters['groupId'])) {
            $studyGroup = StudyGroup::findOne($activeFilters['groupId']);
            $panelHeader .= ' - ' . $studyGroup->study_group_name;
        }

        return $panelHeader;
    }

    /**
     * @return array
     */
    private function getPrograms(): array
    {
        return Programme::find()->select(['prog_code', 'prog_full_name'])->where(['status' => 'ACTIVE'])->asArray()
            ->all();
    }

    /**
     * @return array
     */
    private function getProgramCurr(): array
    {
        return (new Query())
            ->select(['pc.prog_curriculum_desc', 'prog.prog_code', 'prog.prog_full_name'])
            ->from('smis.org_programme_curriculum pc')
            ->innerJoin('smis.org_programmes prog', 'prog.prog_id=pc.prog_id')
            ->where(['pc.status' => 'ACTIVE'])
            ->all();
    }

    /**
     * @return array
     */
    private function getAcademicLevels(): array
    {
        return AcademicLevel::find()->orderBy(['academic_level' => SORT_ASC])->asArray()->all();
    }

    /**
     * @return array
     */
    private function getStudyGroups(): array
    {
        return StudyGroup::find()->orderBy(['study_group_id' => SORT_ASC])
            ->where(['status' => 'ACTIVE'])->asArray()->all();
    }

    /**
     * Get only the semesters created for a year, program, level
     * @return array
     */
    private function getSemesters(): array
    {
        $filters = $this->activeFilters;

        $query = ProgCurrSemesterGroup::find()->alias('psg')
            ->select([
                'psg.prog_curriculum_sem_group_id',
                'psg.prog_curriculum_semester_id',
                'psg.programme_level'
            ])
            ->joinWith(['progCurrSemester ps' => function (ActiveQuery $q) {
                $q->select([
                    'ps.prog_curriculum_semester_id',
                    'ps.acad_session_semester_id',
                    'prog_curriculum_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['progCurrSemester.academicSessionSemester ass' => function (ActiveQuery $q) {
                $q->select([
                    'ass.acad_session_semester_id',
                    'ass.acad_session_id',
                    'ass.semester_code',
                    'ass.acad_session_semester_desc'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['progCurrSemester.academicSessionSemester.academicSession acs' => function (ActiveQuery $q) {
                $q->select([
                    'acs.acad_session_id',
                    'acs.acad_session_name'
                ]);
            }], true, 'INNER JOIN')
            ->where(['acs.acad_session_name' => $filters['year']]);

        if (!empty($filters['level'])) {
            $query->andWhere(['psg.programme_level' => $filters['level']]);
        }

        if (!empty($filters['progCode'])) {
            $program = Programme::find()->select(['prog_id'])->where(['prog_code' => $filters['progCode']])->asArray()->one();
            $programCurr = ProgrammeCurriculum::find()->select(['prog_curriculum_id'])->where(['prog_id' => $program['prog_id']])
                ->orderBy(['prog_curriculum_id' => SORT_DESC])->asArray()->one();
            if (!empty($programCurr)) {
                $query->andWhere(['ps.prog_curriculum_id' => $programCurr['prog_curriculum_id']]);
            }
        }

        $query->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $semesterGroups = $dataProvider->getModels();

        $semesters = [];
        $semester = [];
        foreach ($semesterGroups as $semesterGroup) {
            $level = $semesterGroup['programme_level'];
            $acadSessSem = $semesterGroup['progCurrSemester']['academicSessionSemester'];
            $semester['id'] = $acadSessSem['acad_session_semester_id'];
            $semester['code'] = $acadSessSem['semester_code'];
            $semester['description'] = $acadSessSem['acad_session_semester_desc'] . ' - Year ' . $level;
            $semester['progCurrSemGroupId'] = $semesterGroup['prog_curriculum_sem_group_id'];
            $semesters[] = $semester;
        }

        return $semesters;
    }
}
