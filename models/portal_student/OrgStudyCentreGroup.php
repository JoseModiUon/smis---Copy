<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_study_centre_group".
 *
 * @property int $study_centre_group_id
 * @property int $study_centre_id
 * @property int $study_group_id
 * @property string $status
 *
 * @property OrgProgCurrSemesterGroup[] $orgProgCurrSemesterGroups
 * @property OrgStudyCentre $studyCentre
 * @property OrgStudyGroup $studyGroup
 */
class OrgStudyCentreGroup extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.org_study_centre_group';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['study_centre_id', 'study_group_id'], 'required'],
            [['study_centre_id', 'study_group_id'], 'default', 'value' => null],
            [['study_centre_id', 'study_group_id'], 'integer'],
            [['status'], 'string', 'max' => 10],
            [['study_centre_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgStudyCentre::className(), 'targetAttribute' => ['study_centre_id' => 'study_centre_id']],
            [['study_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgStudyGroup::className(), 'targetAttribute' => ['study_group_id' => 'study_group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'study_centre_group_id' => 'Study Centre Group ID',
            'study_centre_id' => 'Study Centre ID',
            'study_group_id' => 'Study Group ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[OrgProgCurrSemesterGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgCurrSemesterGroups()
    {
        return $this->hasMany(OrgProgCurrSemesterGroup::className(), ['study_centre_group_id' => 'study_centre_group_id']);
    }

    /**
     * Gets query for [[StudyCentre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudyCentre()
    {
        return $this->hasOne(OrgStudyCentre::className(), ['study_centre_id' => 'study_centre_id']);
    }

    /**
     * Gets query for [[StudyGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudyGroup()
    {
        return $this->hasOne(OrgStudyGroup::className(), ['study_group_id' => 'study_group_id']);
    }
}
