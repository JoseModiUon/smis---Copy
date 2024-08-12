<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_academic_progress_status".
 *
 * @property int $progress_status_id
 * @property string $progress_status_name
 *
 * @property SmAcademicProgress[] $smAcademicProgresses
 */
class SmAcademicProgressStatus extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_academic_progress_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['progress_status_id', 'progress_status_name'], 'required'],
            [['progress_status_id'], 'default', 'value' => null],
            [['progress_status_id'], 'integer'],
            [['progress_status_name'], 'string', 'max' => 150],
            [['progress_status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'progress_status_id' => 'Progress Status ID',
            'progress_status_name' => 'Progress Status Name',
        ];
    }

    /**
     * Gets query for [[SmAcademicProgresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmAcademicProgresses()
    {
        return $this->hasMany(SmAcademicProgress::class, ['progress_status_id' => 'progress_status_id']);
    }
}
