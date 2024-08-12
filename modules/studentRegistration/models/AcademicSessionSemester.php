<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_academic_session_semester".
 *
 * @property int $acad_session_semester_id
 * @property int $acad_session_id
 * @property int $semester_code
 * @property string|null $acad_session_semester_desc
 */
class AcademicSessionSemester extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_academic_session_semester';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['acad_session_id', 'semester_code'], 'required'],
            [['acad_session_id', 'semester_code'], 'default', 'value' => null],
            [['acad_session_id', 'semester_code'], 'integer'],
            [['acad_session_semester_desc'], 'string', 'max' => 50],
            [['acad_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicSession::class, 'targetAttribute' => ['acad_session_id' => 'acad_session_id']],
            [['semester_code'], 'exist', 'skipOnError' => true, 'targetClass' => SemesterCode::class, 'targetAttribute' => ['semester_code' => 'semester_code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'acad_session_semester_id' => 'Acad Session Semester ID',
            'acad_session_id' => 'Acad Session ID',
            'semester_code' => 'Semester Code',
            'acad_session_semester_desc' => 'Acad Session Semester Desc',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAcademicSession(): ActiveQuery
    {
        return $this->hasOne(AcademicSession::class, ['acad_session_id' => 'acad_session_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSemesterCode(): ActiveQuery
    {
        return $this->hasOne(SemesterCode::class, ['semester_code' => 'semester_code']);
    }
}
