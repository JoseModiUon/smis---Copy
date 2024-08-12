<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "student_sponsor".
 *
 * @property int $sponsor_id
 * @property string $sponsor_name
 */
class StudentSponsor extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.student_sponsor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sponsor_id', 'sponsor_name'], 'required'],
            [['sponsor_id'], 'default', 'value' => null],
            [['sponsor_id'], 'integer'],
            [['sponsor_name'], 'string', 'max' => 150],
            [['sponsor_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sponsor_id' => 'Sponsor ID',
            'sponsor_name' => 'Sponsor Name',
        ];
    }
}
