<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_intake_source".
 *
 * @property int $source_id
 * @property string $source
 *
 * @property SmAdmittedStudent[] $smAdmittedStudents
 */
class IntakeSource extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_intake_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source_id', 'source'], 'required'],
            [['source_id'], 'default', 'value' => null],
            [['source_id'], 'integer'],
            [['source'], 'string', 'max' => 15],
            [['source_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'source_id' => 'Source ID',
            'source' => 'Source',
        ];
    }

    /**
     * Gets query for [[SmAdmittedStudents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmAdmittedStudents()
    {
        return $this->hasMany(SmAdmittedStudent::class, ['source_id' => 'source_id']);
    }
}
