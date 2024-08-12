<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_reg_documents".
 *
 * @property int $document_id
 * @property string $document_name
 * @property string $document_desc
 * @property bool $required
 *
 * @property SmRegRequiredDocument[] $smRegRequiredDocuments
 */
class SmRegDocuments extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_reg_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_name', 'document_desc'], 'required'],
            [['required'], 'boolean'],
            [['document_name'], 'string', 'max' => 150],
            [['document_desc'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'document_id' => 'Document ID',
            'document_name' => 'Document Name',
            'document_desc' => 'Document Desc',
            'required' => 'Required',
        ];
    }

    /**
     * Gets query for [[SmRegRequiredDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmRegRequiredDocuments()
    {
        return $this->hasMany(SmRegRequiredDocument::class, ['fk_document_id' => 'document_id']);
    }
}
