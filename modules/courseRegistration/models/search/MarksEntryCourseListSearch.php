<?php

namespace app\modules\courseRegistration\models\search;

use app\models\CrProgrammeCurrLectureTimetable;
use app\models\CrProgCurrTimetable;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrSemesterGroup;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * OrgProgCurrSemesterGroupSearch represents the model behind the search form of `app\models\OrgProgCurrSemesterGroup`.
 */
class MarksEntryCourseListSearch extends CrProgCurrTimetable
{
    public $curriculum;
    public $studyGroup;

    public $programmeLevel;
    public $prog_curriculum_id;
    public $progCurrCourse;
    public $progCurrSemGroup;
    public $examMode;
    public $examVenue;
    public $acad_session_id;
    public $study_group_id;
    public $study_centre_id;
    public $semester_type_id;
    public $semester_code;
    public $programme_level_id;
    public $academic_level_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'prog_curriculum_id', 'acad_session_id', 'study_centre_id', 'study_group_id', 'semester_type_id', 'semester_code', 'academic_level_id'
            ], 'required'],
            [[
                'exam_date', 'progCurrCourse', 'progCurrSemGroup',
                'examMode', 'examVenue', 'programme_level_id',
                'semester_code', 'academic_level_id',

                'semester_type_id', 'programmeLevel', 'curriculum', 'studyGroup'
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $filters = $params['ClassListSearch'];

        $acadeSess = \app\modules\studentRegistration\models\AcademicSession::findOne($filters['acad_session_id']);
        $year = $acadeSess->acad_session_name;

        $progCurr = \app\modules\studentRegistration\models\ProgrammeCurriculum::findOne($filters['prog_curriculum_id']);
        $prog = \app\modules\studentRegistration\models\Programme::findOne($progCurr->prog_id);
        $progCode = $prog->prog_code;

        $acadLevel = \app\modules\studentRegistration\models\AcademicLevel::findOne($filters['academic_level_id']);
        $level = $acadLevel->academic_level;

        $partialMarksheetId = $year . '_' . $progCode . '_' . $level . '_' . $filters['semester_code'] . '_' . $filters['study_group_id'];

        $query = CrProgCurrTimetable::find()
            ->select([
                'org_courses.course_id',
                'org_courses.course_code',
                'org_courses.course_name',
                // 'org_days.day_name',
                // 'cr_class_groups.class_description',
                // 'cr_programme_curr_lecture_timetable.start_time',
                // 'cr_programme_curr_lecture_timetable.end_time',
                'cr_prog_curr_timetable.timetable_id',
                // 'cr_programme_curr_lecture_timetable.lecture_timetable_id',
                'org_academic_levels.academic_level_name',
                'org_semester_code.semster_name',
                // 'org_rooms.room_name',

            ])
            ->joinWith(['orgProgCurrCourse' => function (ActiveQuery $r) {
                $r->joinWith(['course', 'semesterCode', 'academicLevels']);
            }])
            ->joinWith(['progCurriculumSemGroup' => function ($t) {
                $t->joinWith(['progCurriculumSemester' => function (ActiveQuery $r) {
                    $r->joinWith(['progCurriculum', 'orgSemesterType']);
                    $r->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
                        $a->joinWith('acadSession');
                    }]);
                }]);
                $t->joinWith(['studyCentreGroup' => function (ActiveQuery $k) {
                    $k->joinWith(['studyCentre', 'studyGroup']);
                }]);
            }])
            ->where(['like', 'mrksheet_id', $partialMarksheetId . '%', false])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 20,
            ],

        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->orderBy([
            'org_academic_levels.academic_level_id' => SORT_ASC,
            'org_semester_code.semester_code' => SORT_ASC,
        ]);

        return $dataProvider;
    }
}
