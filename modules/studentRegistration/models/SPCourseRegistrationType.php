<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/18/2023
 * @time: 11:57 AM
 */
declare(strict_types=1);
namespace app\modules\studentRegistration\models;

use Yii;
use yii\db\Connection;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "smisportal.cr_course_reg_type".
 *
 * @property int $course_reg_type_id
 * @property string $course_reg_type_code FA,SUPP,RETAKE
 * @property string|null $course_reg_type_name FIRST ATTEMPT, SUPPLIMENTARY,RETAKE
 * @property string|null $course_reg_entry_type ORIGINAL,PASSMARK
 */
class SPCourseRegistrationType extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.cr_course_reg_type';
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
            [['course_reg_type_id', 'course_reg_type_code'], 'required'],
            [['course_reg_type_id'], 'default', 'value' => null],
            [['course_reg_type_id'], 'integer'],
            [['course_reg_type_code'], 'string', 'max' => 10],
            [['course_reg_type_name', 'course_reg_entry_type'], 'string', 'max' => 20],
            [['course_reg_type_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'course_reg_type_id' => 'Course Reg Type ID',
            'course_reg_type_code' => 'Course Reg Type Code',
            'course_reg_type_name' => 'Course Reg Type Name',
            'course_reg_entry_type' => 'Course Reg Entry Type',
        ];
    }
}
