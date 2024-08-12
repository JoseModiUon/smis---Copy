<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_unit".
 *
 * @property int $unit_id
 * @property string $unit_code
 * @property string $unit_name
 *
 * @property OrgUnitHistory[] $orgUnitHistories
 */
class OrgUnit extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_code', 'unit_name'], 'required'],
            [['unit_code'], 'string', 'max' => 6],
            [['unit_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'unit_id' => 'Unit ID',
            'unit_code' => 'Unit Code',
            'unit_name' => 'Unit Name',
        ];
    }

    /**
     * Gets query for [[OrgUnitHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgUnitHistories()
    {
        return $this->hasMany(OrgUnitHistory::className(), ['org_unit_id' => 'unit_id']);
    }

}
