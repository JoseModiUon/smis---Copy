<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_academic_session".
 *
 * @property int $acad_session_id
 * @property string $acad_session_name
 * @property string|null $acad_session_desc
 * @property string $start_date
 * @property string $end_date
 */
class AcademicSession extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_academic_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['acad_session_name', 'start_date', 'end_date'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['acad_session_name'], 'string', 'max' => 50],
            [['acad_session_desc'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'acad_session_id' => 'Acad Session ID',
            'acad_session_name' => 'Acad Session Name',
            'acad_session_desc' => 'Acad Session Desc',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }
}
