<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgKuccpsProgMap;

/**
 * OrgKuccpsProgMapSearch represents the model behind the search form of `app\models\OrgKuccpsProgMap`.
 */
class OrgKuccpsProgMapSearch extends OrgKuccpsProgMap
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_map_id'], 'integer'],
            [['kuccps_prog_code', 'uon_prog_code'], 'safe'],
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
        $query = OrgKuccpsProgMap::find();

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
            'prog_map_id' => $this->prog_map_id,
        ]);

        $query->andFilterWhere(['ilike', 'kuccps_prog_code', $this->kuccps_prog_code])
            ->andFilterWhere(['ilike', 'uon_prog_code', $this->uon_prog_code]);

        return $dataProvider;
    }
}
