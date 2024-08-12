<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_semester_code".
 *
 * @property int $semester_code
 * @property string $semster_name
 *
 * @property OrgAcademicSessionSemester[] $orgAcademicSessionSemesters
 */
class OrgSemesterCode extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_semester_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semester_code', 'semster_name'], 'required'],
            [['semester_code'], 'default', 'value' => null],
            [['semester_code'], 'integer'],
            [['semster_name'], 'string', 'max' => 30],
            [['semester_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'semester_code' => 'Semester Code',
            'semster_name' => 'Semster Name',
        ];
    }

    /**
     * Gets query for [[OrgAcademicSessionSemesters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgAcademicSessionSemesters()
    {
        return $this->hasMany(OrgAcademicSessionSemester::className(), ['semester_code' => 'semester_code']);
    }
}
