<?php

namespace app\models\portal_student;

use Yii;
use app\models\OrgStudyGroup;
use app\models\OrgStudyCentre;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_building".
 *
 * @property int $building_id
 * @property string|null $building_code
 * @property string|null $building_name
 * @property int|null $floors
 * @property int|null $study_center
 */
class OrgBuilding extends \yii\db\ActiveRecord
{
    use UserTracking;

    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.org_building';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['floors', 'study_center'], 'default', 'value' => null],
            [['floors', 'study_center'], 'integer'],
            [['building_code'], 'string', 'max' => 30],
            [['building_name'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'building_id' => 'Building ID',
            'building_code' => 'Building Code',
            'building_name' => 'Building Name',
            'floors' => 'Floors',
            'study_center' => 'Study Center',
        ];
    }
	 public function getStudycenter()
    {
        return $this->hasOne(OrgStudyCentre::className(), ['study_centre_id' => 'study_center']);
    }
}
