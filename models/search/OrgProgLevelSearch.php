<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgLevel;

/**
 * OrgProgLevelSearch represents the model behind the search form of `app\models\OrgProgLevel`.
 */
class OrgProgLevelSearch extends OrgProgLevel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['programme_level_id'], 'integer'],
            [['programme_level_name'], 'safe'],
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
        $query = OrgProgLevel::find();

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
            'programme_level_id' => $this->programme_level_id,
        ]);

        $query->andFilterWhere(['ilike', 'programme_level_name', $this->programme_level_name]);

        return $dataProvider;
    }
}
