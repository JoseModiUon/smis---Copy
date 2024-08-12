<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.ex_grading_system".
 *
 * @property int $grading_system_id
 * @property string $grading_system_name
 * @property string $grading_system_desc
 * @property string $status
 */
class ExGradingSystem extends \yii\db\ActiveRecord
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
        return 'smisportal.ex_grading_system';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grading_system_name', 'grading_system_desc'], 'required'],
            [['grading_system_name'], 'string', 'max' => 20],
            [['grading_system_desc'], 'string', 'max' => 60],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'grading_system_id' => 'Grading System ID',
            'grading_system_name' => 'Grading System Name',
            'grading_system_desc' => 'Grading System Desc',
            'status' => 'Status',
        ];
    }




    public function getGradingDetail()
    {
        return $this->hasMany(ExGradingSystemDetail::class, ['grading_system_id' => 'grading_system_id']);
    }
}
