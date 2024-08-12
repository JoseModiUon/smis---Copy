<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CrProgCurrTimetable;
use app\models\OrgProgCurrCourse;
use app\models\OrgProgCurrSemesterGroup;
use yii\db\ActiveQuery;

/**
 * CrProgCurrTimetableSearch represents the model behind the search form of `app\models\CrProgCurrTimetable`.
 */
class CrProgCurrTimetableCreateSearch extends CrProgCurrTimetable
{
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
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'integer'],
            [[
                'exam_date', 'progCurrCourse', 'progCurrSemGroup',
                'examMode', 'examVenue', 'programme_level_id',
                'prog_curriculum_id', 'semester_code', 'academic_level_id',
                'acad_session_id', 'study_group_id', 'study_centre_id',
                'semester_type_id'
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
        // dd($this->semester_code);
        $query = OrgProgCurrCourse::find();

        // add conditions that should always apply here
        $query->joinWith(['course', 'academicLevels', 'semesterCode']);

        $query->joinWith(['progCurriculum' => function (ActiveQuery $q) {
            $q->joinWith(['orgProgCurrSemesters' => function (ActiveQuery $r) {
                $r->joinWith(['acadSessionSemester' => function (ActiveQuery $t) {
                    $t->joinWith(['acadSession', 'semesterCode']);
                }]);
                $r->joinWith(['orgProgCurrSemesterGroups' => function (ActiveQuery $p) {
                    $p->joinWith(['studyCentreGroup' => function (ActiveQuery $f) {
                        $f->joinWith(['studyCentre', 'studyGroup']);
                    }]);
                    $p->joinWith(['programmeLevel']);
                }]);
                $r->joinWith(['orgSemesterType']);
            }]);
        }]);

        // $query = OrgProgCurrSemesterGroup::find()
        //     ->joinWith(['progCurriculumSemester' => function (ActiveQuery $q) {
        //         $q->joinWith(['progCurriculum' => function (Activequer $pr) {
        //             $pr->joinWith(['orgProgCurrCourses' => function (ActiveQuery $or) {
        //                 $or->joinWith(['course', 'progCurriculum', 'semesterCode', 'academicLevels']);
        //             }]);
        //         }]);
        //         $q->joinWith(['orgSemesterType']);
        //         $q->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
        //             $a->joinWith('acadSession');
        //         }]);
        //     }])
        //     ->joinWith(['studyCentreGroup' => function (ActiveQuery $q) {
        //         $q->joinWith(['studyCentre', 'studyGroup']);
        //     }])
        //     ->joinWith(['programmeLevel']);
        // ->joinWith(['orgProgCurrCourse' => function (ActiveQuery $q) {
        //     $q->joinWith(['course', 'progCurriculum', 'semesterCode', 'academicLevels']);
        // }])
        // ->joinWith(['examMode']);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            // 'org_prog_curr_course.prog_curriculum_course_id' => $this->prog_curriculum_course_id,
            // 'org_prog_curr_semester_group.prog_curriculum_sem_group_id' => $this->prog_curriculum_sem_group_id,
            'org_programme_curriculum.prog_curriculum_id' => $this->prog_curriculum_id,
            'org_academic_session.acad_session_id' => $this->acad_session_id,
            'org_study_group.study_group_id' => $this->study_group_id,
            'org_study_centre.study_centre_id' => $this->study_centre_id,
            'org_semester_type.semester_type_id' => $this->semester_type_id,
            'org_semester_code.semester_code' => $this->semester_code,
            'org_prog_level.programme_level_id' => $this->programme_level_id,

        ]);

        return $dataProvider;
    }
}
