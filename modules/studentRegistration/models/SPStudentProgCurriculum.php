<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "smisportal.sm_student_programme_curriculum".
 *
 * @property int $student_prog_curriculum_id
 * @property int $student_id
 * @property string $registration_number
 * @property int $prog_curriculum_id
 * @property int $student_category_id
 * @property int $adm_refno
 * @property int $status_id
 */
class SPStudentProgCurriculum extends ActiveRecord
{
//    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_programme_curriculum';
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
            [['student_prog_curriculum_id', 'student_id', 'registration_number', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'required'],
            [['student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'default', 'value' => null],
            [['student_prog_curriculum_id', 'student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'integer'],
            [['registration_number'], 'string', 'max' => 20],
//            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalOrgProgrammeCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
//            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmStudent::class, 'targetAttribute' => ['student_id' => 'student_id']],
//            [['student_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmStudentCategory::class, 'targetAttribute' => ['student_category_id' => 'std_category_id']],
//            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmStudentStatus::class, 'targetAttribute' => ['status_id' => 'status_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['student_prog_curriculum_id' => "string", 'student_id' => "string", 'registration_number' => "string",
        'prog_curriculum_id' => "string", 'student_category_id' => "string", 'adm_refno' => "string", 'status_id' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'student_id' => 'Student ID',
            'registration_number' => 'Registration Number',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'student_category_id' => 'Student Category ID',
            'adm_refno' => 'Adm Refno',
            'status_id' => 'Status ID',
        ];
    }
}
