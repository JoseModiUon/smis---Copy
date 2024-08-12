<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CrCourseRegType;

/**
 * CrCourseRegTypeSearch represents the model behind the search form of `app\models\CrCourseRegType`.
 */
class CrCourseRegTypeSearch extends CrCourseRegType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_reg_type_id'], 'integer'],
            [['course_reg_type_code', 'course_reg_type_name', 'course_reg_entry_type'], 'safe'],
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
        $query = CrCourseRegType::find();

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
            'course_reg_type_id' => $this->course_reg_type_id,
        ]);

        $query->andFilterWhere(['ilike', 'course_reg_type_code', $this->course_reg_type_code])
            ->andFilterWhere(['ilike', 'course_reg_type_name', $this->course_reg_type_name])
            ->andFilterWhere(['ilike', 'course_reg_entry_type', $this->course_reg_entry_type]);

        return $dataProvider;
    }
}
