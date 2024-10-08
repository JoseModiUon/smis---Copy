<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgCourses;
use yii\db\ActiveQuery;

/**
 * OrgCoursesSearch represents the model behind the search form of `app\models\OrgCourses`.
 */
class OrgAddCoursesSearch extends OrgCourses
{
    public $category;
    public $orgUnit;
    public $academicLevels;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'level_of_study', 'semester', 'academic_hours', 'org_unit_id', 'billing_factor', 'category_id'], 'integer'],
            [['course_code', 'course_name', 'status', 'category', 'orgUnit', 'academicLevels'], 'safe'],
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
    public function search($params, $moreParams = [])
    {

        // dd($params);
        $query = OrgCourses::find();

        // add conditions that should always apply here
        $query->joinWith(['orgUnit', 'category', 'academicLevels', 'semesterCode']);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 70
            ]
        ]);

        $dataProvider->sort->attributes['orgUnit'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_unit.unit_name' => SORT_ASC],
            'desc' => ['org_unit.unit_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['category'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['cr_course_category.category_name' => SORT_ASC],
            'desc' => ['cr_course_category.category_name' => SORT_DESC],
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
            'course_id' => $this->course_id,
            'level_of_study' => $this->level_of_study,
            'semester' => $this->semester,
            'academic_hours' => $this->academic_hours,
            'org_unit_id' => $this->org_unit_id,
            'billing_factor' => $this->billing_factor,
            'category_id' => $this->category_id,
            'org_academic_levels.academic_level' => $this->level_of_study,
            'org_courses.status' => 'ACTIVE'
            // 'org_prog_curr_course.status' => 'ACTIVE',
        ]);

        $query->andFilterWhere(['ilike', 'course_code', $this->course_code])
            ->andFilterWhere(['ilike', 'course_name', $this->course_name])
            ->andFilterWhere(['ilike', 'cr_course_category.category_name', $this->category])
            ->andFilterWhere(['ilike', 'org_unit.unit_name', $this->orgUnit])
            ->andFilterWhere(['ilike', 'org_academic_levels.academic_level_name', $this->academicLevels])
            ->andFilterWhere(['ilike', 'org_courses.status', $this->status]);

        $query->orderBy([
            // 'org_prog_curr_course.course_id' => SORT_ASC,
            'org_academic_levels.academic_level' => SORT_ASC,
            'org_semester_code.semester_code' => SORT_ASC,
            'course_id' => SORT_ASC,
        ]);

        return $dataProvider;
    }
}
