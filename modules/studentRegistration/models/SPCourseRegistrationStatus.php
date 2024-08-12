<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/18/2023
 * @time: 11:56 AM
 */
declare(strict_types=1);
namespace app\modules\studentRegistration\models;

use Yii;
use yii\db\Connection;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "smisportal.cr_course_reg_status".
 *
 * @property int $course_reg_status_id
 * @property string $course_reg_status_name
 */
class SPCourseRegistrationStatus extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.cr_course_reg_status';
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
