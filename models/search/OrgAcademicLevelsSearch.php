<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgAcademicLevels;

/**
 * OrgAcademicLevelsSearch represents the model behind the search form of `app\models\OrgAcademicLevels`.
 */
class OrgAcademicLevelsSearch extends OrgAcademicLevels
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academic_level_id', 'academic_level', 'academic_level_order'], 'integer'],
            [['academic_level_name', 'status'], 'safe'],
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
        $query = OrgAcademicLevels::find();

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
            'academic_level_id' => $this->academic_level_id,
            'academic_level' => $this->academic_level,
            'academic_level_order' => $this->academic_level_order,
        ]);

        $query->andFilterWhere(['ilike', 'academic_level_name', $this->academic_level_name])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
