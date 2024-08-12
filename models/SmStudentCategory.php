<?php

namespace app\models;

use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_category".
 *
 * @property int $std_category_id
 * @property string $std_category_name
 *
 * @property SmRegRequiredDocument[] $smRegRequiredDocuments
 * @property SmStudentProgrammeCurriculum[] $smStudentProgrammeCurriculums
 */
class SmStudentCategory extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['std_category_name'], 'required'],
            [['std_category_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'std_category_id' => 'Std Category ID',
            'std_category_name' => 'Std Category Name',
        ];
    }

    /**
     * Gets query for [[SmRegRequiredDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmRegRequiredDocuments()
    {
        return $this->hasMany(SmRegRequiredDocument::class, ['fk_category_id' => 'std_category_id']);
    }

    /**
     * Gets query for [[SmStudentProgrammeCurriculums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentProgrammeCurriculums()
    {
        return $this->hasMany(SmStudentProgrammeCurriculum::class, ['student_category_id' => 'std_category_id']);
    }
}
