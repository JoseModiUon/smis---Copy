<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_semester_type".
 *
 * @property int $sem_type_id
 * @property string $sem_type
 */
class OrgSemesterType extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_semester_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semester_type'], 'required'],
            [['semester_type'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'semester_type_id' => 'Sem Type ID',
            'semester_type' => 'Semester Type',
        ];
    }

    /**
     * Gets query for [[OrgProgCurrSemester]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgCurrSemester()
    {
        return $this->hasMany(OrgSemesterType::className(), ['semester_type_id' => 'semester_type_id']);
    }
}
