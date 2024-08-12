<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_stud_submitted_document".
 *
 * @property int $student_document_id
 * @property int $required_document_id
 * @property string $document_path
 * @property string|null $ip_address
 * @property string $upload_date
 * @property string $verify_status
 * @property string|null $doc_comments
 * @property int $adm_refno
 *
 * @property SmAdmittedStudent $admRefno
 * @property SmRegRequiredDocument $requiredDocument
 */
class SmStudSubmittedDocument extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_stud_submitted_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['required_document_id', 'document_path', 'upload_date', 'verify_status', 'adm_refno'], 'required'],
            [['required_document_id', 'adm_refno'], 'default', 'value' => null],
            [['required_document_id', 'adm_refno'], 'integer'],
            [['upload_date'], 'safe'],
            [['document_path', 'doc_comments'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 60],
            [['verify_status'], 'string', 'max' => 20],
            [['adm_refno'], 'exist', 'skipOnError' => true, 'targetClass' => SmAdmittedStudent::class, 'targetAttribute' => ['adm_refno' => 'adm_refno']],
            [['required_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmRegRequiredDocument::class, 'targetAttribute' => ['required_document_id' => 'required_document_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_document_id' => 'Student Document ID',
            'required_document_id' => 'Required Document ID',
            'document_path' => 'Document Path',
            'ip_address' => 'Ip Address',
            'upload_date' => 'Upload Date',
            'verify_status' => 'Verify Status',
            'doc_comments' => 'Doc Comments',
            'adm_refno' => 'Adm Refno',
        ];
    }

    /**
     * Gets query for [[AdmRefno]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdmRefno()
    {
        return $this->hasOne(SmAdmittedStudent::class, ['adm_refno' => 'adm_refno']);
    }

    /**
     * Gets query for [[RequiredDocument]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequiredDocument()
    {
        return $this->hasOne(SmRegRequiredDocument::class, ['required_document_id' => 'required_document_id']);
    }
}
