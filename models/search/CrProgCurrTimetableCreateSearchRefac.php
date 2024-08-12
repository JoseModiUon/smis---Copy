<?php

namespace app\models\search;

use app\models\OrgProgCurrSemester;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrSemesterGroup;
use app\models\CrProgCurrTimetable;
use Codeception\Lib\Generator\Actor;
use yii\db\ActiveQuery;
use yii\db\Query;
use app\models\OrgProgCurrCourse;

/**
 * OrgProgCurrSemesterGroupSearch represents the model behind the search form of `app\models\OrgProgCurrSemesterGroup`.
 */
class CrProgCurrTimetableCreateSearchRefac extends OrgProgCurrSemesterGroup
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

    public $course;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_sem_group_id', 'prog_curriculum_semester_id', 'study_centre_group_id', 'programme_level'], 'integer'],
            [[
                'prog_curriculum_id', 'acad_session_id', 'study_centre_id',
                'study_group_id', 'semester_type_id', 'semester_code', 'programme_level_id'
            ], 'required'],
            [[
                'exam_date', 'progCurrCourse', 'progCurrSemGroup',
                'examMode', 'examVenue',
                'programmeLevel', 'curriculum', 'studyGroup', 'course'
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curriculum_id' => 'Programme Curriculum',
            'acad_session_id' => 'Academic Session',
            'study_centre_id' => 'Study Centre',
            'study_group_id' => 'Study Group',
            'semester_type_id' => 'Semester Type',
            'semester_code' => 'Semester',
            'academic_level_id' => 'Programme Level'

        ];
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

        $query = OrgProgCurrCourse::find()
            ->select([
                'org_prog_curr_semester_group.prog_curriculum_sem_group_id',
                'org_courses.course_code',
                // 'cr_prog_curr_timetable.timetable_id',
                'org_academic_levels.academic_level_name',
                'org_courses.course_name',
                'org_courses.course_id',
                'org_semester_code.semster_name',
                'org_prog_curr_semester.prog_curriculum_semester_id',
                'org_prog_curr_course.prog_curriculum_course_id',
                'org_prog_curr_course.level_of_study',
                // 'org_prog_level.programme_level_name',
            ])
            ->joinWith(['course', 'semesterCode', 'academicLevels'])
            ->joinWith(['progCurriculum' => function (ActiveQuery $t) {
                $t->joinWith(['orgProgCurrSemesters' => function (ActiveQuery $q) {
                    $q->joinWith(['orgSemesterType']);
                    $q->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
                        $a->joinWith('acadSession');
                    }]);
                    $q->joinWith(['orgProgCurrSemesterGroups' => function (ActiveQuery $r) {
                        $r->joinWith(['studyCentreGroup' => function (ActiveQuery $k) {
                            $k->joinWith(['studyCentre', 'studyGroup']);
                        }]);
                    }]);
                }]);
            }]);


        $query->where([
            'org_programme_curriculum.prog_curriculum_id' => $params['prog_curriculum_id'],
            'org_academic_session.acad_session_id' => $params['acad_session_id'],
            'org_semester_type.semester_type_id' => $params['semester_type_id'],
            'org_semester_code.semester_code' => $params['semester_code'],
            'org_study_centre.study_centre_id' => $params['study_centre_id'],
            'org_study_group.study_group_id' => $params['study_group_id'],
            // 'org_prog_level.programme_level_id' => $params['programme_level_id'],
            // 'org_academic_levels.academic_level' => $params['programme_level_id'],
            'org_prog_curr_course.status' => 'ACTIVE',
            'org_prog_curr_course.level_of_study' => $params['programme_level_id'],
        ]);

        $query->asArray();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 20,
            ],

        ]);


        // dd($dataProvider->getModels());


        $this->populateFormValues($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['ilike', 'org_courses.course_name', $this->course]);

        $query->orderBy([
            'org_prog_curr_course.level_of_study' => SORT_ASC,
            'org_semester_code.semester_code' => SORT_ASC,
            'org_courses.course_code' => SORT_ASC,
        ]);

        return $dataProvider;
    }
}
