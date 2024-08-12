<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_rooms".
 *
 * @property int $room_id
 * @property string|null $room_code
 * @property string|null $room_name
 * @property int|null $fk_building_id
 * @property int|null $fk_floor_id
 * @property int|null $room_capacity
 * @property int|null $fk_room_type
 * @property int|null $fk_room_status_id
 */
class OrgRooms extends \yii\db\ActiveRecord
{
    use UserTracking;

    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.org_rooms';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['room_name'], 'unique'],
            //[['room_id', 'fk_building_id', 'fk_floor_id', 'room_capacity', 'fk_room_type', 'fk_room_status_id'], 'default', 'value' => null],
            [['fk_building_id', 'fk_floor_id', 'room_capacity', 'fk_room_type', 'fk_room_status_id'], 'default', 'value' => null],
            [['fk_building_id', 'room_capacity', 'fk_room_type', 'fk_room_status_id'], 'integer'],
            [['room_code'], 'string', 'max' => 40],
            [['room_name'], 'string', 'max' => 80],

            //[['room_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'room_id' => 'Room ID',
            'room_code' => 'Room Code',
            'room_name' => 'Room Name',
            'fk_building_id' => 'Fk Building ID',
            'fk_floor_id' => 'Fk Floor ID',
            'room_capacity' => 'Room Capacity',
            'fk_room_type' => 'Fk Room Type',
            'fk_room_status_id' => 'Fk Room Status ID',
        ];
    }

    public function getRoomType()
    {
        return $this->hasOne(OrgRoomType::class, ['room_type_id' => 'fk_room_type']);
    }

    public function getBuilding()
    {
        return $this->hasOne(OrgBuilding::class, ['building_id' => 'fk_building_id']);
    }
    public function getFkRoomStatus()
    {

        return $this->hasOne(OrgRoomStatus::class, ['room_status_id' => 'fk_room_status_id']);
    }
    public function getFkFloor()
    {

        return $this->hasOne(OrgRoomFloors::class, ['floor_id' => 'fk_floor_id']);
    }
}
