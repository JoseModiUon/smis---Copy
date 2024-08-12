<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 4/26/2023
 */
namespace app\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.um_apps".
 *
 * @property int $id
 * @property string $short_name
 * @property string $full_name
 */
class App extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return SMIS_DB_SCHEMA . '.um_apps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['short_name', 'full_name'], 'required'],
            [['short_name'], 'string', 'max' => 24],
            [['full_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'short_name' => 'Short Name',
            'full_name' => 'Full Name',
        ];
    }
}
