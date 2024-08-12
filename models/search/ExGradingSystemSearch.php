<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExGradingSystem;

/**
 * ExGradingSystemSearch represents the model behind the search form of `app\models\ExGradingSystem`.
 */
class ExGradingSystemSearch extends ExGradingSystem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grading_system_id'], 'integer'],
            [['grading_system_name', 'grading_system_desc', 'status'], 'safe'],
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
        $query = ExGradingSystem::find();

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
            'grading_system_id' => $this->grading_system_id,
        ]);

        $query->andFilterWhere(['ilike', 'grading_system_name', $this->grading_system_name])
            ->andFilterWhere(['ilike', 'grading_system_desc', $this->grading_system_desc])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
