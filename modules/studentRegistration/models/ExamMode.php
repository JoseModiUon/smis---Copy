<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 11:15 AM
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.ex_mode".
 *
 * @property int $exam_mode_id
 * @property string $exam_mode_name
 */
class ExamMode extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.ex_mode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['exam_mode_id', 'exam_mode_name'], 'required'],
            [['exam_mode_id'], 'default', 'value' => null],
            [['exam_mode_id'], 'integer'],
            [['exam_mode_name'], 'string', 'max' => 30],
            [['exam_mode_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'exam_mode_id' => 'Exam Mode ID',
            'exam_mode_name' => 'Exam Mode Name',
        ];
    }
}
