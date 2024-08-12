<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_room_floors".
 *
 * @property int $floor_id
 * @property string $floor_name
 */
class OrgRoomFloors extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_room_floors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['floor_name'], 'required'],
            // [['floor_id'], 'default', 'value' => null],
            [['floor_id'], 'integer'],
            [['floor_name'], 'string'],
            [['floor_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'floor_id' => 'Floor ID',
            'floor_name' => 'Floor Name',
        ];
    }
}
