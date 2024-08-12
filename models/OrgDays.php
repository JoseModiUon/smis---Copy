<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_days".
 *
 * @property int $day_id 1,2,3,4,5,6,7
 * @property string $day_name
 */
class OrgDays extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_days';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['day_id', 'day_name'], 'required'],
            [['day_id'], 'default', 'value' => null],
            [['day_id'], 'integer'],
            [['day_name'], 'string'],
            [['day_id', 'day_id'], 'unique', 'targetAttribute' => ['day_id', 'day_id']],
            [['day_id', 'day_name'], 'unique', 'targetAttribute' => ['day_id', 'day_name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'day_id' => 'Day ID',
            'day_name' => 'Day Name',
        ];
    }
}
