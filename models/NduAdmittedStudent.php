<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_admitted_student".
 *
 * @property int $adm_refno
 * @property string|null $kcse_index_no
 * @property string|null $kcse_year when uploading make this field mandatory
 * @property string|null $primary_phone_no
 * @property string|null $alternative_phone_no
 * @property string|null $primary_email
 * @property string|null $alternative_email
 * @property string|null $post_code
 * @property string|null $post_address
 * @property string|null $town
 * @property string|null $kuccps_prog_code
 * @property string $uon_prog_code
 * @property string|null $national_id
 * @property string|null $birth_cert_no
 * @property int $source_id
 * @property string|null $passport_no
 * @property string $admission_status to take care of a case where an admission is revoked or recalled for the sake of module II default status pre-admission
 * @property int|null $application_refno to link to applicant incase a report of admitted student is required
 * @property int $intake_code
 * @property int $student_category_id
 * @property string|null $password default refno
 * @property bool|null $doc_submission_status
 * @property string|null $primary_email_salt
 * @property string|null $secondary_email_salt
 * @property string|null $primary_email_verified_date
 * @property string|null $secondary_email_verified_date
 * @property string|null $surname
 * @property string|null $other_names
 * @property string|null $primary_phone_country_code
 * @property string|null $alternative_phone_country_code
 * @property string $gender
 * @property string|null $clearance_status indicates clearance status of a student. PENDING, CLEARED, NOT CLEARED
 * @property string|null $password_changed_date
 * @property string|null $service
 * @property string|null $service_number
 * @property string|null $nationality
 * @property string|null $date_of_birth
 */
class NduAdmittedStudent extends \yii\db\ActiveRecord
{
    use UserTracking;

    public $admit_list;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_admitted_student';
    }


    // public function rules()
    // {
    //     return [
    //         [['uon_prog_code', 'source_id', 'intake_code', 'student_category_id', 'gender'], 'required'],
    //         [['source_id', 'application_refno', 'intake_code', 'student_category_id'], 'default', 'value' => null],
    //         [['source_id', 'application_refno', 'intake_code', 'student_category_id'], 'integer'],
    //         [['doc_submission_status'], 'boolean'],
    //         [['primary_email_verified_date', 'secondary_email_verified_date', 'password_changed_date', 'date_of_birth'], 'safe'],
    //         [['kcse_index_no', 'post_address', 'kuccps_prog_code', 'uon_prog_code', 'national_id', 'birth_cert_no', 'passport_no', 'service'], 'string', 'max' => 20],
    //         [['kcse_year', 'post_code', 'primary_phone_country_code', 'alternative_phone_country_code'], 'string', 'max' => 10],
    //         [['primary_phone_no', 'alternative_phone_no', 'town', 'surname', 'nationality'], 'string', 'max' => 50],
    //         [['primary_email', 'alternative_email', 'password'], 'string', 'max' => 100],
    //         [['admission_status', 'clearance_status', 'service_number'], 'string', 'max' => 30],
    //         [['primary_email_salt', 'secondary_email_salt'], 'string', 'max' => 255],
    //         [['other_names'], 'string', 'max' => 150],
    //         [['gender'], 'string', 'max' => 1],
    //         [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmIntakeSource::class, 'targetAttribute' => ['source_id' => 'source_id']],
    //         [['intake_code'], 'exist', 'skipOnError' => true, 'targetClass' => SmIntakes::class, 'targetAttribute' => ['intake_code' => 'intake_code']],
    //     ];
    // }
    /**
    * {@inheritdoc}
    */
   public function rules()
   {
       return [
           [['uon_prog_code', 'source_id', 'intake_code'], 'required'],
           // [['kcse_index_no', 'kcse_year',], 'required'],
       //    [['source_id', 'application_refno', 'intake_code', 'student_category_id'], 'default', 'value' => null],
       //    [['source_id', 'application_refno', 'intake_code', 'student_category_id'], 'integer'],
       //    [['doc_submission_status'], 'boolean'],
           [['kcse_index_no','kcse_year'], 'unique','targetAttribute' => 'kcse_index_no'],
       //    [['kcse_index_no','kcse_year','primary_phone_no'], 'unique'],
       //    [['kcse_index_no','kcse_year','primary_email'], 'unique'],
           [['primary_email_verified_date', 'secondary_email_verified_date'], 'safe'],
       //    [['kcse_index_no', 'kuccps_prog_code', 'uon_prog_code'], 'string', 'max' => 20],
       //    [['kcse_year'], 'string', 'max' => 10],
           [['gender'], 'string', 'max' => 1],
       //    [['primary_phone_no', 'alternative_phone_no', 'post_address', 'national_id', 'birth_cert_no', 'passport_no'], 'string', 'max' => 12],
       //    [['primary_email', 'alternative_email'], 'string', 'max' => 25],
           [['post_code'], 'string', 'max' => 5],
           [['town'], 'string', 'max' => 100],
       //    [['password'], 'string', 'max' => 100],
       //    [['primary_email_salt', 'secondary_email_salt'], 'string', 'max' => 255],
       //    [['surname'], 'string', 'max' => 50],
       //    [['other_names'], 'string', 'max' => 150],
           [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => IntakeSource::class, 'targetAttribute' => ['source_id' => 'source_id']],
           [['intake_code'], 'exist', 'skipOnError' => true, 'targetClass' => Intakes::class, 'targetAttribute' => ['intake_code' => 'intake_code']],
           [['admit_list'], 'file'],
       ];
   }




    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adm_refno' => 'Adm Refno',
            'kcse_index_no' => 'Kcse Index No',
            'kcse_year' => 'Kcse Year',
            'primary_phone_no' => 'Primary Phone No',
            'alternative_phone_no' => 'Alternative Phone No',
            'primary_email' => 'Primary Email',
            'alternative_email' => 'Alternative Email',
            'post_code' => 'Post Code',
            'post_address' => 'Post Address',
            'town' => 'Town',
            'kuccps_prog_code' => 'Kuccps Prog Code',
            'uon_prog_code' => 'Uon Prog Code',
            'national_id' => 'National ID',
            'birth_cert_no' => 'Birth Cert No',
            'source_id' => 'Source ID',
            'passport_no' => 'Passport No',
            'admission_status' => 'Admission Status',
            'application_refno' => 'Application Refno',
            'intake_code' => 'Intake Code',
            'student_category_id' => 'Student Category ID',
            'password' => 'Password',
            'doc_submission_status' => 'Doc Submission Status',
            'primary_email_salt' => 'Primary Email Salt',
            'secondary_email_salt' => 'Secondary Email Salt',
            'primary_email_verified_date' => 'Primary Email Verified Date',
            'secondary_email_verified_date' => 'Secondary Email Verified Date',
            'surname' => 'Surname',
            'other_names' => 'Other Names',
            'primary_phone_country_code' => 'Primary Phone Country Code',
            'alternative_phone_country_code' => 'Alternative Phone Country Code',
            'gender' => 'Gender',
            'clearance_status' => 'Clearance Status',
            'password_changed_date' => 'Password Changed Date',
            'service' => 'Service',
            'service_number' => 'Service Number',
            'nationality' => 'Nationality',
            'date_of_birth' => 'Date Of Birth',
        ];
    }
}
