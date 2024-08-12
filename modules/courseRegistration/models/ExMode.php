<?php

namespace app\modules\courseRegistration\models;


use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "ex_mode".
 *
 * @property int $exam_mode_id
 * @property string $exam_mode_name
 *
 * @property CrProgCurrTimetable[] $crProgCurrTimetables
 */
class ExMode extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.ex_mode';
    }


    /**  
     * {@inheritdoc}
     */

    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exam_mode_name'], 'required'],
            //[['exam_mode_id'], 'default', 'value' => null],
            [['exam_mode_id'], 'integer'],
            [['exam_mode_name'], 'string', 'max' => 30],
            [['exam_mode_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'exam_mode_id' => 'Exam Mode ID',
            'exam_mode_name' => 'Exam Mode Name',
        ];
    }

    /**
     * Gets query for [[CrProgCurrTimetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCrProgCurrTimetables()
    {
        return $this->hasMany(CrProgCurrTimetable::class, ['exam_mode' => 'exam_mode_id']);
    }
}
