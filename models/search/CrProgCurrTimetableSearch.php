<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CrProgCurrTimetable;
use yii\db\ActiveQuery;

/**
 * CrProgCurrTimetableSearch represents the model behind the search form of `app\models\CrProgCurrTimetable`.
 */
class CrProgCurrTimetableSearch extends CrProgCurrTimetable
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
                'prog_curriculum_id', 'semester_code',
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
        $query = CrProgCurrTimetable::find()
            ->joinWith(['progCurriculumSemGroup' => function (ActiveQuery $r) {
                $r->joinWith(['progCurriculumSemester' => function (ActiveQuery $q) {
                    $q->joinWith(['progCurriculum', 'orgSemesterType']);
                    $q->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
                        $a->joinWith('acadSession');
                    }]);
                }]);
                $r->joinWith(['studyCentreGroup' => function (ActiveQuery $q) {
                    $q->joinWith(['studyCentre', 'studyGroup']);
                }]);
                $r->joinWith(['programmeLevel']);
            }])
            ->joinWith(['orgProgCurrCourse' => function (ActiveQuery $q) {
                $q->joinWith(['course', 'progCurriculum', 'semesterCode', 'academicLevels']);
            }])
            ->joinWith(['examMode']);


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
            'timetable_id' => $this->timetable_id,
            'org_prog_curr_course.prog_curriculum_course_id' => $this->prog_curriculum_course_id,
            'org_prog_curr_semester_group.prog_curriculum_sem_group_id' => $this->prog_curriculum_sem_group_id,
            'org_programme_curriculum.prog_curriculum_id' => $this->prog_curriculum_id,
            'org_academic_session.acad_session_id' => $this->acad_session_id,
            'org_study_group.study_group_id' => $this->study_group_id,
            'org_study_centre.study_centre_id' => $this->study_centre_id,
            'org_semester_type.semester_type_id' => $this->semester_type_id,
            'org_semester_code.semester_code' => $this->semester_code,
            'org_prog_level.programme_level_id' => $this->programme_level_id,
            'exam_date' => $this->exam_date,
            'exam_venue' => $this->exam_venue,
            'exam_mode' => $this->exam_mode,
        ]);

        $query->orderBy([
            'org_semester_code.semester_code' => SORT_ASC

        ]);

        return $dataProvider;
    }
}
