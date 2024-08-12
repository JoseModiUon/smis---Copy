<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgUnitCourse;

/**
 * OrgUnitCourseSearch represents the model behind the search form of `app\models\OrgUnitCourse`.
 */
class OrgUnitCourseSearch extends OrgUnitCourse
{

    public $course;
    public $orgUnit;
    public $teachingUnit;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_unit_course_id', 'course_id', 'org_unit_id', 'org_teaching_id', 'user_id'], 'integer'],
            [['start_date', 'end_date','orgUnit','course','teachingUnit'], 'safe'],
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
        $query = OrgUnitCourse::find();

        // add conditions that should always apply here
        $query->joinWith(['teachingUnit','course','orgUnit']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['teachingUnit'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_unit.unit_name' => SORT_ASC],
            'desc' => ['org_unit.unit_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['orgUnit'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_unit.unit_name' => SORT_ASC],
            'desc' => ['org_unit.unit_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['course'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_courses.course_name' => SORT_ASC],
            'desc' => ['org_courses.course_name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'org_unit_course_id' => $this->org_unit_course_id,
            'course_id' => $this->course_id,
            'org_unit_id' => $this->org_unit_id,
            'org_teaching_id' => $this->org_teaching_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user_id' => $this->user_id,
        ]);
        $query->andFilterWhere(['ilike', 'smis.org_unit.unit_name', $this->teachingUnit])
            ->andFilterWhere(['ilike', 'smis.org_unit.unit_name', $this->orgUnit])
            ->andFilterWhere(['ilike', 'smis.org_courses.course_name', $this->course]);

        return $dataProvider;
    }
}
