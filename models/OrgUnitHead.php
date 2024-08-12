<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_unit_head".
 *
 * @property int $unit_head_id
 * @property string $unit_head_name PRINCIPAL,DIRECTOR,CHAIRMAN
 * @property string $status
 *
 * @property OrgUnitHistory[] $orgUnitHistories
 */
class OrgUnitHead extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_unit_head';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_head_name'], 'required'],
            [['unit_head_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'unit_head_id' => 'Unit Head ID',
            'unit_head_name' => 'Unit Head Name',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[OrgUnitHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgUnitHistories()
    {
        return $this->hasMany(OrgUnitHistory::className(), ['unit_head_id' => 'unit_head_id']);
    }
}
