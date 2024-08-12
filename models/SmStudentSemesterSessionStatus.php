<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_semester_session_status".
 *
 * @property int $status_id
 * @property string $status_name
 *
 * @property SmStudentSemSessionProgress[] $smStudentSemSessionProgresses
 */
class SmStudentSemesterSessionStatus extends \yii\db\ActiveRecord
{
    use UserTracking;

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_semester_session_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'status_name'], 'required'],
            [['status_id'], 'default', 'value' => null],
            [['status_id'], 'integer'],
            [['status_name'], 'string'],
            [['status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status_id' => 'Status ID',
            'status_name' => 'Status Name',
        ];
    }

    /**
     * Gets query for [[SmStudentSemSessionProgresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentSemSessionProgresses()
    {
        return $this->hasMany(SmStudentSemSessionProgress::className(), ['reporting_status_id' => 'status_id']);
    }
}
