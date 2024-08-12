<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/11/2023
 * @time: 5:45 PM
 */

namespace app\modules\studentRegistration\models;

use Yii;
use yii\db\Connection;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
use yii\base\InvalidConfigException;

class SPMarksheet extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.ex_marksheet';
    }

    /**
     * @return Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['student_course_reg_id'], 'required'],
            [['student_course_reg_id', 'final_mark'], 'default', 'value' => null],
            [['student_course_reg_id', 'final_mark'], 'integer'],
            [['course_work_mark', 'exam_mark'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'marksheet_id' => 'Marksheet ID',
            'student_course_reg_id' => 'Student Course Reg ID',
            'course_work_mark' => 'Course Work Mark',
            'exam_mark' => 'Exam Mark',
            'final_mark' => 'Final Mark',
        ];
    }
}
