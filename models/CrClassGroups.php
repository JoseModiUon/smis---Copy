<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.cr_class_groups".
 *
 * @property int $class_code
 * @property string|null $class_description
 */
class CrClassGroups extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.cr_class_groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'class_code' => 'Class Code',
            'class_description' => 'Class Description',
        ];
    }
}
