<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use JetBrains\PhpStorm\ArrayShape;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smisportal.sm_intake_source".
 *
 * @property int $source_id
 * @property string $source
 */
class IntakeSource extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_intake_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['source'], 'required'],
            [['source'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['source_id' => "string", 'source' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'source_id' => 'Source ID',
            'source' => 'Source',
        ];
    }
}
