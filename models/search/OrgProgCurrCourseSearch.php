<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrCourse;

/**
 * OrgProgCurrCourseSearch represents the model behind the search form of `app\models\OrgProgCurrCourse`.
 */
class OrgProgCurrCourseSearch extends OrgProgCurrCourse
{
    public $course;
    public $progCurriculum;
    public $academicLevels;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_course_id', 'prog_curriculum_id', 'course_id', 'credit_factor', 'level_of_study', 'semester',], 'integer'],
            [['credit_hours'], 'number'],
            [['status', 'course', 'progCurriculum', 'academicLevels'], 'safe'],
            [['has_course_work'], 'boolean'],
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
        $query = OrgProgCurrCourse::find();

        // add conditions that should always apply here
        $query->joinWith(['progCurriculum', 'course', 'academicLevels']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);
        $dataProvider->sort->attributes['course'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_courses.course_code' => SORT_ASC],
            'desc' => ['org_courses.course_code' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['progCurriculum'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_programme_curriculum.prog_curriculum_desc' => SORT_ASC],
            'desc' => ['org_programme_curriculum.prog_curriculum_desc' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['academicLevels'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_academic_levels.academic_level_name' => SORT_ASC],
            'desc' => ['org_academic_levels.academic_level_name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'prog_curriculum_course_id' => $this->prog_curriculum_course_id,
            'org_programme_curriculum.prog_curriculum_id' => $this->prog_curriculum_id,
            'course_id' => $this->course_id,
            'credit_factor' => $this->credit_factor,
            'credit_hours' => $this->credit_hours,
            'level_of_study' => $this->level_of_study,
            'semester' => $this->semester,
            // 'org_prog_curr_course.status' => 'ACTIVE',
            'has_course_work' => $this->has_course_work,
            'org_prog_curr_course.status' => 'ACTIVE'
        ]);

        $query
            // ->andFilterWhere(['ilike', 'org_prog_curr_course.status', $this->status])
            ->andFilterWhere(['ilike', 'smis.org_courses.course_code', $this->course])
            ->orFilterWhere(['ilike', 'smis.org_courses.course_name', $this->course])
            ->andFilterWhere(['ilike', 'smis.org_programme_curriculum.prog_curriculum_desc', $this->progCurriculum])
            ->andFilterWhere(['ilike', 'org_academic_levels.academic_level_name', $this->academicLevels]);
        
        $query->orderBy([
            'org_prog_curr_course.level_of_study' => SORT_ASC,
            'org_prog_curr_course.semester' => SORT_ASC,
            'org_courses.course_id' => SORT_ASC
        ]);


        return $dataProvider;
    }
}
