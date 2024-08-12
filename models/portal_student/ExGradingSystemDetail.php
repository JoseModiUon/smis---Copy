<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "ex_grading_system_detail".
 *
 * @property int $grading_system_detail_id
 * @property int $grading_system_id
 * @property float $lower_bound
 * @property float $upper_bound
 * @property string $grade
 * @property float|null $grade_point
 * @property string $is_active
 *
 * @property ExGradingSystem $gradingSystem
 */
class ExGradingSystemDetail extends \yii\db\ActiveRecord
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
        return 'smisportal.ex_grading_system_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grading_system_detail_id', 'grading_system_id', 'lower_bound', 'upper_bound', 'grade'], 'required'],
            [['grading_system_detail_id', 'grading_system_id'], 'default', 'value' => null],
            [['grading_system_detail_id', 'grading_system_id'], 'integer'],
            [['lower_bound', 'upper_bound', 'grade_point'], 'number'],
            [['grade'], 'string', 'max' => 2],
            [['is_active'], 'string', 'max' => 10],
            [['grading_system_detail_id'], 'unique'],
            [['grading_system_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExGradingSystem::class, 'targetAttribute' => ['grading_system_id' => 'grading_system_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'grading_system_detail_id' => 'Grading System Detail ID',
            'grading_system_id' => 'Grading System ID',
            'lower_bound' => 'Lower Bound',
            'upper_bound' => 'Upper Bound',
            'grade' => 'Grade',
            'grade_point' => 'Grade Point',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * Gets query for [[GradingSystem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGradingSystem()
    {
        return $this->hasOne(ExGradingSystem::class, ['grading_system_id' => 'grading_system_id']);
    }
}
