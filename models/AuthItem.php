<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 4/26/2023
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.um_auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property int|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class AuthItem extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return SMIS_DB_SCHEMA . '.um_auth_item';
    }

    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'data', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['type', 'data', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::class, 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'App ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getApp(): ActiveQuery
    {
        return $this->hasOne(App::class, ['id' => 'data']);
    }
}
