<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use Yii;
use yii\db\Connection;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "smisportal.sm_academic_progress".
 *
 * @property int $academic_progress_id
 * @property int $acad_session_id
 * @property int $academic_level_id
 * @property int $student_prog_curriculum_id how_the_student_acquired_the_status
 * @property int $progress_status_id
 * @property int $current_status
 */
class SPAcademicProgress extends ActiveRecord
{
//    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_academic_progress';
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
            [['academic_progress_id', 'acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'required'],
            [['academic_progress_id', 'acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'default', 'value' => null],
            [['academic_progress_id', 'acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'integer'],
            [['academic_progress_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'academic_progress_id' => 'Academic Progress ID',
            'acad_session_id' => 'Acad Session ID',
            'academic_level_id' => 'Academic Level ID',
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'progress_status_id' => 'Progress Status ID',
            'current_status' => 'Current Status',
        ];
    }
}
