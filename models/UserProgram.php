<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 19/2/2024
 */
namespace app\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_degree_prog_user".
 *
 * @property int $degree_user_id
 * @property int|null $user_id
 * @property int|null $programme_id
 * @property string $assignment_date
 * @property string $effective_date
 * @property string $end_date
 * @property string $status
 * @property int|null $granted_by
 */
class UserProgram extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_degree_prog_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'programme_id', 'granted_by'], 'default', 'value' => null],
            [['user_id', 'programme_id', 'granted_by'], 'integer'],
            [['assignment_date', 'effective_date', 'end_date', 'status'], 'required'],
            [['assignment_date', 'effective_date', 'end_date'], 'safe'],
            [['status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'degree_user_id' => 'Degree User ID',
            'user_id' => 'User ID',
            'programme_id' => 'Programme ID',
            'assignment_date' => 'Assignment Date',
            'effective_date' => 'Effective Date',
            'end_date' => 'End Date',
            'status' => 'Status',
            'granted_by' => 'Granted By',
        ];
    }
}
