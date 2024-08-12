<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_study_group".
 *
 * @property int $study_group_id
 * @property string $study_group_name
 * @property string $status
 */
class StudyGroup extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_study_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['study_group_name'], 'required'],
            [['study_group_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'study_group_id' => 'Study Group ID',
            'study_group_name' => 'Study Group Name',
            'status' => 'Status',
        ];
    }
}
