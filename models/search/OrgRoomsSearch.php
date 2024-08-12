<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgRooms;

/**
 * OrgRoomsSearch represents the model behind the search form of `app\models\OrgRooms`.
 */
class OrgRoomsSearch extends OrgRooms
{
    /**
     * {@inheritdoc}
     */
	 
	public $building;
	public $fkRoomStatus;
	public $roomType;
    public $fkFloor;
    public function rules()
    {
        return [
            [['room_id', 'fk_building_id', 'fk_floor_id', 'room_capacity', 'fk_room_type', 'fk_room_status_id'], 'integer'],
            [['room_code', 'room_name','roomType','fkFloor','building','fkRoomStatus'], 'safe'],
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
        $query = OrgRooms::find();
		   
		$query->joinWith(['building','roomType','fkFloor','fkRoomStatus']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$dataProvider->sort->attributes['building'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_building.building_name' => SORT_ASC],
            'desc' => ['org_building.building_name' => SORT_DESC],
        ];
		$dataProvider->sort->attributes['roomType'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_room_type.room_type_name' => SORT_ASC],
            'desc' => ['org_room_type.room_type_name' => SORT_DESC],
        ];
		$dataProvider->sort->attributes['fkFloor'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_room_floors.floor_name' => SORT_ASC],
            'desc' => ['org_room_floors.floor_name' => SORT_DESC],
        ];
		$dataProvider->sort->attributes['fkRoomStatus'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_room_status.room_status_desc' => SORT_ASC],
            'desc' => ['org_room_status.room_status_desc' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'room_id' => $this->room_id,
            'fk_building_id' => $this->fk_building_id,
            'fk_floor_id' => $this->fk_floor_id,
            'room_capacity' => $this->room_capacity,
            'fk_room_type' => $this->fk_room_type,
            'fk_room_status_id' => $this->fk_room_status_id,
        ]);
		

        $query->andFilterWhere(['ilike', 'room_code', $this->room_code])
            ->andFilterWhere(['ilike', 'room_name', $this->room_name])
			 ->andFilterWhere(['ilike', 'smis.org_room_floors.floor_name', $this->fkFloor])
            ->andFilterWhere(['ilike', 'smis.org_room_status.room_status_desc', $this->fkRoomStatus])
            ->andFilterWhere(['ilike', 'smis.org_building.building_name', $this->building])
            ->andFilterWhere(['ilike', 'smis.org_room_type.room_type_name', $this->roomType]);

        return $dataProvider;
    }
}
