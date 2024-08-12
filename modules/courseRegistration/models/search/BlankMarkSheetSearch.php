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
class BlankMarkSheetSearch extends CrProgCurrTimetable
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
    public $academic_level;
    public $academic_level_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'prog_curriculum_id', 'acad_session_id', 'study_centre_id', 'study_group_id', 'semester_type_id', 'semester_code', 'academic_level',
            ], 'required'],
            [[
                'exam_date', 'progCurrCourse', 'progCurrSemGroup',
                'examMode', 'examVenue', 'programme_level_id',
                'semester_code', 'academic_level', 'academic_level_id',

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


    private function populateFormValues($params)
    {
        foreach ($params as $key => $param) {
            if (array_key_exists($key, get_object_vars($this))) {
                $this->$key = $param;
            }
        }
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


        $query = CrProgCurrTimetable::find()
            ->select([
                'org_courses.course_id',
                'org_courses.course_code',
                'org_courses.course_name',
                'cr_prog_curr_timetable.timetable_id',
                'org_academic_levels.academic_level_name',
                'org_semester_code.semster_name',
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
            }]);

        $query->andWhere([
            'org_programme_curriculum.prog_curriculum_id' => $params['prog_curriculum_id'] ?? null,
            'org_academic_session.acad_session_id' => $params['acad_session_id'] ?? null,
            // 'org_study_centre.study_centre_id' => $params['study_centre_id'] ?? null,
            'org_study_group.study_group_id' => $params['study_group_id'] ?? null,
            // 'org_semester_type.semester_type_id' => $params['semester_type_id'] ?? null,
            'org_semester_code.semester_code' => $params['semester_code'] ?? null,
            'org_prog_curr_course.level_of_study' => $params['academic_level'] ?? null,
            'org_prog_curr_course.status' => 'ACTIVE',
        ])->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 20,
            ],

        ]);

        $this->populateFormValues($params);

        if (!$this->validate()) {
            // uncomme  nt the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



        $query->orderBy([
            'org_academic_levels.academic_level' => SORT_ASC,
            'org_semester_code.semester_code' => SORT_ASC,
        ]);


        return $dataProvider;
    }
}
