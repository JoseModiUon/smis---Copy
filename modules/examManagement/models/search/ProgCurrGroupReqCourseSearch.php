<?php

namespace app\modules\examManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\examManagement\models\ProgCurrGroupReqCourse;

/**
 * ProgCurrGroupReqCourseSearch represents the model behind the search form of `app\modules\examManagement\models\ProgCurrGroupReqCourse`.
 */
class ProgCurrGroupReqCourseSearch extends ProgCurrGroupReqCourse
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curr_group_req_course_id', 'prog_curr_group_requirement_id', 'prog_curriculum_course_id', 'credit_factor'], 'integer'],
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
        $query = ProgCurrGroupReqCourse::find();

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
            'prog_curr_group_req_course_id' => $this->prog_curr_group_req_course_id,
            'prog_curr_group_requirement_id' => $this->prog_curr_group_requirement_id,
            'prog_curriculum_course_id' => $this->prog_curriculum_course_id,
            'credit_factor' => $this->credit_factor,
        ]);

        return $dataProvider;
    }
}
