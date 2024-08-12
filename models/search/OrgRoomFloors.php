<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgRoomFloors as OrgRoomFloorsModel;

/**
 * OrgRoomFloors represents the model behind the search form of `app\models\OrgRoomFloors`.
 */
class OrgRoomFloors extends OrgRoomFloorsModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['floor_id'], 'integer'],
            [['floor_name'], 'safe'],
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
        $query = OrgRoomFloorsModel::find();

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
            'floor_id' => $this->floor_id,
        ]);

        $query->andFilterWhere(['ilike', 'floor_name', $this->floor_name]);

        return $dataProvider;
    }
}
