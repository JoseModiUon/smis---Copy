<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_mentor_relationship".
 *
 * @property int $mentor_relationship_id
 * @property string $relationship_name
 * @property string $description
 */
class SmMentorRelationship extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_mentor_relationship';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mentor_relationship_id', 'relationship_name', 'description'], 'required'],
            [['mentor_relationship_id'], 'default', 'value' => null],
            [['mentor_relationship_id'], 'integer'],
            [['relationship_name', 'description'], 'string'],
            [['mentor_relationship_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mentor_relationship_id' => 'Mentor Relationship ID',
            'relationship_name' => 'Relationship Name',
            'description' => 'Description',
        ];
    }
}
