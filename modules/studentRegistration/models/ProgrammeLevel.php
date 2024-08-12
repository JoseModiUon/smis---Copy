<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;


use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_prog_level".
 *
 * @property int $programme_level_id
 * @property string $programme_level_name
 */
class ProgrammeLevel extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_prog_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['programme_level_name'], 'required'],
            [['programme_level_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'programme_level_id' => 'Programme Level ID',
            'programme_level_name' => 'Programme Level Name',
        ];
    }
}
