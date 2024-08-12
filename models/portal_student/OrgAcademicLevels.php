<?php

namespace app\models\portal_student;

use Yii;
use app\models\SmAcademicProgress;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_academic_levels".
 *
 * @property int $academic_level_id
 * @property int $academic_level
 * @property string $academic_level_name
 * @property int|null $academic_level_order
 * @property string $status
 *
 * @property SmAcademicProgress[] $smAcademicProgresses
 */
class OrgAcademicLevels extends \yii\db\ActiveRecord
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
        return 'smisportal.org_academic_levels';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academic_level', 'academic_level_name'], 'required'],
            [['academic_level', 'academic_level_order'], 'default', 'value' => null],
            [['academic_level', 'academic_level_order'], 'integer'],
            [['academic_level_name'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'academic_level_id' => 'Academic Level ID',
            'academic_level' => 'Level',
            'academic_level_name' => 'Name',
            'academic_level_order' => 'Order',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[SmAcademicProgresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmAcademicProgresses()
    {
        return $this->hasMany(SmAcademicProgress::className(), ['academic_level_id' => 'academic_level_id']);
    }
}
