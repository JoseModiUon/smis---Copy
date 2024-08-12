<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_prog_category".
 *
 * @property int $prog_cat_id
 * @property string $prog_cat_code
 * @property string $prog_cat_name
 * @property int $prog_cat_order
 * @property string $status
 *
 * @property OrgProgrammes[] $orgProgrammes
 */
class OrgProgCategory extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_prog_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_cat_code', 'prog_cat_name'], 'required'],
            [['prog_cat_order'], 'default', 'value' => null],
            [['prog_cat_order'], 'integer'],
            [['prog_cat_code', 'status'], 'string', 'max' => 20],
            [['prog_cat_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_cat_id' => 'Prog Cat ID',
            'prog_cat_code' => 'Prog Cat Code',
            'prog_cat_name' => 'Prog Cat Name',
            'prog_cat_order' => 'Prog Cat Order',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[OrgProgrammes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgrammes()
    {
        return $this->hasMany(OrgProgrammes::className(), ['prog_cat_id' => 'prog_cat_id']);
    }
}
