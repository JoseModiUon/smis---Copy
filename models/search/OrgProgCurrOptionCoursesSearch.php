<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrOptionCourses;

/**
 * OrgProgCurrOptionCoursesSearch represents the model behind the search form of `app\models\OrgProgCurrOptionCourses`.
 */
class OrgProgCurrOptionCoursesSearch extends OrgProgCurrOptionCourses
{
    public $courses;
    public $option;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_course_id', 'option_id', 'course_id'], 'integer'],
            [['course_type', 'degree_code','option','courses'], 'safe'],
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
        $query = OrgProgCurrOptionCourses::find();
        $query->joinWith(['courses','option']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['option'] = [

            'asc' => ['org_prog_curr_option.option_name' => SORT_ASC],
            'desc' => ['org_prog_curr_option.option_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['courses'] = [

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
            'option_course_id' => $this->option_course_id,
            'option_id' => $this->option_id,
            'course_id' => $this->course_id,
        ]);

        $query->andFilterWhere(['ilike', 'org_prog_curr_option.option_name', $this->option])
            ->andFilterWhere(['ilike', 'org_courses.course_name', $this->courses])
            ->andFilterWhere(['ilike', 'course_type', $this->course_type]);

        return $dataProvider;
    }
}
