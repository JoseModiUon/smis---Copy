<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smisportal.org_programmes".
 *
 * @property int $prog_id
 * @property string $prog_code
 * @property string $prog_short_name
 * @property string $prog_full_name
 * @property int $prog_type_id
 * @property int $prog_cat_id
 * @property string $status
 */
class Programme extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_programmes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_id', 'prog_code', 'prog_short_name', 'prog_full_name', 'prog_type_id', 'prog_cat_id'], 'required'],
            [['prog_id', 'prog_type_id', 'prog_cat_id'], 'default', 'value' => null],
            [['prog_id', 'prog_type_id', 'prog_cat_id'], 'integer'],
            [['prog_code'], 'string', 'max' => 6],
            [['prog_short_name'], 'string', 'max' => 100],
            [['prog_full_name'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 20],
            [['prog_id'], 'unique'],
//            [['prog_cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalOrgProgCategory::class, 'targetAttribute' => ['prog_cat_id' => 'prog_cat_id']],
//            [['prog_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalOrgProgType::class, 'targetAttribute' => ['prog_type_id' => 'prog_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_id' => 'Prog ID',
            'prog_code' => 'Prog Code',
            'prog_short_name' => 'Prog Short Name',
            'prog_full_name' => 'Prog Full Name',
            'prog_type_id' => 'Prog Type ID',
            'prog_cat_id' => 'Prog Cat ID',
            'status' => 'Status',
        ];
    }
}
