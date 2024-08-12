<?php

namespace app\models;

use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_reg_required_document".
 *
 * @property int $required_document_id
 * @property int $fk_document_id
 * @property int $fk_category_id
 *
 * @property SmStudentCategory $fkCategory
 * @property SmRegDocuments $fkDocument
 * @property SmStudSubmittedDocument[] $smStudSubmittedDocuments
 */
class SmRegRequiredDocument extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_reg_required_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_document_id', 'fk_category_id'], 'required'],
            [['fk_document_id', 'fk_category_id'], 'default', 'value' => null],
            [['fk_document_id', 'fk_category_id'], 'integer'],
            [['fk_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmRegDocuments::class, 'targetAttribute' => ['fk_document_id' => 'document_id']],
            [['fk_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmStudentCategory::class, 'targetAttribute' => ['fk_category_id' => 'std_category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'required_document_id' => 'Required Document ID',
            'fk_document_id' => 'Fk Document ID',
            'fk_category_id' => 'Fk Category ID',
        ];
    }

    /**
     * Gets query for [[FkCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkCategory()
    {
        return $this->hasOne(SmStudentCategory::class, ['std_category_id' => 'fk_category_id']);
    }

    /**
     * Gets query for [[FkDocument]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkDocument()
    {
        return $this->hasOne(SmRegDocuments::class, ['document_id' => 'fk_document_id']);
    }

    /**
     * Gets query for [[SmStudSubmittedDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudSubmittedDocuments()
    {
        return $this->hasMany(SmStudSubmittedDocument::class, ['required_document_id' => 'required_document_id']);
    }
}
