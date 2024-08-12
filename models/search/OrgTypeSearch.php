<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgRoomType;

/**
 * OrgTypeSearch represents the model behind the search form of `app\models\OrgRoomType`.
 */
class OrgTypeSearch extends OrgRoomType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_type_id'], 'integer'],
            [['room_type_name'], 'safe'],
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
        $query = OrgRoomType::find();

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
            'room_type_id' => $this->room_type_id,
        ]);

        $query->andFilterWhere(['ilike', 'room_type_name', $this->room_type_name]);

        return $dataProvider;
    }
}
