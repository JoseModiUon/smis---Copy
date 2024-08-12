<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_room_status".
 *
 * @property int $room_status_id
 * @property string $room_status_desc
 */
class OrgRoomStatus extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_room_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_status_desc'], 'required'],

          //  [['room_status_id'], 'integer'],
            [['room_status_desc'], 'string'],
         //   [['room_status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'room_status_id' => 'Room Status ID',
            'room_status_desc' => 'Room Status Desc',
        ];
    }
}
