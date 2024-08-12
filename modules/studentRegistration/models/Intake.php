<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use JetBrains\PhpStorm\ArrayShape;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smisportal.sm_intakes".
 *
 * @property int $intake_code
 * @property string $intake_name
 */
class Intake extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_intakes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['intake_name'], 'required'],
            [['intake_name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['intake_code' => "string", 'intake_name' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'intake_code' => 'Intake Code',
            'intake_name' => 'Intake Name',
        ];
    }
}
