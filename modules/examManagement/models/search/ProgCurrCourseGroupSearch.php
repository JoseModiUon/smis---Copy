<?php

namespace app\modules\examManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\examManagement\models\ProgCurrCourseGroup;

/**
 * ProgCurrCourseGroupSearch represents the model behind the search form of `app\modules\examManagement\models\ProgCurrCourseGroup`.
 */
class ProgCurrCourseGroupSearch extends ProgCurrCourseGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_group_id'], 'integer'],
            [['course_group_name', 'course_group_desc', 'course_group_type'], 'safe'],
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
        $query = ProgCurrCourseGroup::find();

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
            'course_group_id' => $this->course_group_id,
        ]);

        $query->andFilterWhere(['ilike', 'course_group_name', $this->course_group_name])
            ->andFilterWhere(['ilike', 'course_group_desc', $this->course_group_desc])
            ->andFilterWhere(['ilike', 'course_group_type', $this->course_group_type]);

        return $dataProvider;
    }
}
