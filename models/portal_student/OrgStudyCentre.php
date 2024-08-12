<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_study_centre".
 *
 * @property int $study_centre_id
 * @property string $study_centre_name
 * @property string $status
 *
 * @property OrgStudyCentreGroup[] $orgStudyCentreGroups
 */
class OrgStudyCentre extends \yii\db\ActiveRecord
{
    use UserTracking;

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
    public static function tableName()
    {
        return 'smisportal.org_study_centre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['study_centre_name'], 'required'],
            [['study_centre_name'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'study_centre_id' => 'Study Centre ID',
            'study_centre_name' => 'Study Centre Name',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[OrgStudyCentreGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgStudyCentreGroups()
    {
        return $this->hasMany(OrgStudyCentreGroup::className(), ['study_centre_id' => 'study_centre_id']);
    }
}
