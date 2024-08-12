<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "smisportal.sm_stud_submitted_document".
 *
 * @property int $student_document_id
 * @property int $required_document_id
 * @property string $document_path
 * @property string|null $ip_address
 * @property string $upload_date
 * @property string $verify_status
 * @property string|null $doc_comments
 * @property int $adm_refno
 */
class SPSubmittedDocument extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_stud_submitted_document';
    }

    /**
     * @return Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['required_document_id', 'document_path', 'upload_date', 'verify_status', 'adm_refno'], 'required'],
            [['required_document_id', 'adm_refno'], 'default', 'value' => null],
            [['required_document_id', 'adm_refno'], 'integer'],
            [['upload_date'], 'safe'],
            [['document_path', 'doc_comments'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 60],
            [['verify_status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['student_document_id' => "string", 'required_document_id' => "string", 'document_path' => "string", 'ip_address' => "string", 'upload_date' => "string", 'verify_status' => "string", 'doc_comments' => "string", 'adm_refno' => "string"])]
    public function attributeLabels(): array
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
}
