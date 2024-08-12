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
class SmIntakeSource extends \app\extended\BaseModel
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
            [['source'], 'required'],
            [['source'], 'string', 'max' => 30],
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
