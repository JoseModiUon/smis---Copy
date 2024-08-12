<?php

namespace app\modules\studentRecords\models;

use Yii;
use yii\db\Connection;
use app\models\SmNameChange;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_name_change_approval".
 *
 * @property int $name_change_approval_id
 * @property int $name_change_id
 * @property string $approval_status pending, review, closed
 * @property string|null $remarks
 * @property string $approved_by
 * @property string $approval_date
 *
 * @property SmNameChange $nameChange
 */
class SPNameChangeApproval extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_name_change_approval';
    }
 /**
     * @return Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_change_id', 'approval_status', 'approved_by', 'approval_date'], 'required'],
            [['name_change_id'], 'default', 'value' => null],
            [['name_change_approval_id', 'name_change_id'], 'integer'],
            [['approval_date'], 'safe'],
            [['approval_status'], 'string', 'max' => 30],
            [['remarks'], 'string', 'max' => 250],
            [['approved_by'], 'string', 'max' => 15],
            [['name_change_approval_id'], 'unique'],
            [['name_change_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmNameChange::className(), 'targetAttribute' => ['name_change_id' => 'name_change_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name_change_approval_id' => 'Name Change Approval ID',
            'name_change_id' => 'Name Change ID',
            'approval_status' => 'Approval Status',
            'remarks' => 'Remarks',
            'approved_by' => 'Approved By',
            'approval_date' => 'Approval Date',
        ];
    }

    /**
     * Gets query for [[NameChange]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNameChange()
    {
        return $this->hasOne(SmNameChange::className(), ['name_change_id' => 'name_change_id']);
    }
}
