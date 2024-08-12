<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 11:11 AM
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
/**
 * This is the model class for table "smis.cr_course_reg_status".
 *
 * @property int $course_reg_status_id
 * @property string $course_reg_status_name
 */
class CourseRegistrationStatus extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.cr_course_reg_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['course_reg_status_id', 'course_reg_status_name'], 'required'],
            [['course_reg_status_id'], 'default', 'value' => null],
            [['course_reg_status_id'], 'integer'],
            [['course_reg_status_name'], 'string'],
            [['course_reg_status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'course_reg_status_id' => 'Course Reg Status ID',
            'course_reg_status_name' => 'Course Reg Status Name',
        ];
    }
}
