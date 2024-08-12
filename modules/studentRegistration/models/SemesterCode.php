<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_semester_code".
 *
 * @property int $semester_code
 * @property string $semster_name
 */
class SemesterCode extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_semester_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['semester_code', 'semster_name'], 'required'],
            [['semester_code'], 'default', 'value' => null],
            [['semester_code'], 'integer'],
            [['semster_name'], 'string', 'max' => 30],
            [['semester_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'semester_code' => 'Semester Code',
            'semster_name' => 'Semster Name',
        ];
    }
}
