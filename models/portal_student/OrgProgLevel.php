<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_prog_level".
 *
 * @property int $programme_level_id
 * @property string $programme_level_name
 *notion
 * @property OrgProgCurrSemesterGroup[] $orgProgCurrSemesterGroups
 */
class OrgProgLevel extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_prog_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['programme_level_id', 'programme_level_name'], 'required'],
            [['programme_level_id'], 'default', 'value' => null],
            [['programme_level_id'], 'integer'],
            [['programme_level_name'], 'string', 'max' => 30],
            [['programme_level_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'programme_level_id' => 'Programme Level ID',
            'programme_level_name' => 'Programme Level Name',
        ];
    }

    /**
     * Gets query for [[OrgProgCurrSemesterGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgCurrSemesterGroups()
    {
        return $this->hasMany(OrgProgCurrSemesterGroup::className(), ['programme_level' => 'programme_level_id']);
    }
}
