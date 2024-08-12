<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_academic_progress_status".
 *
 * @property int $progress_status_id
 * @property string $progress_status_name
 */
class AcademicProgressStatus extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_academic_progress_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['progress_status_name'], 'required'],
            [['progress_status_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'progress_status_id' => 'Progress Status ID',
            'progress_status_name' => 'Progress Status Name',
        ];
    }
}
