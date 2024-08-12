<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExMode;

/**
 * ExModeSearch represents the model behind the search form of `app\models\ExMode`.
 */
class ExModeSearch extends ExMode
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exam_mode_id'], 'integer'],
            [['exam_mode_name'], 'safe'],
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
        $query = ExMode::find();

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
            'exam_mode_id' => $this->exam_mode_id,
        ]);

        $query->andFilterWhere(['ilike', 'exam_mode_name', $this->exam_mode_name]);

        return $dataProvider;
    }
}
