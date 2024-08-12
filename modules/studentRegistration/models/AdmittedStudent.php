<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
use app\modules\feesManagement\models\StudentGroup;

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
 * @property bool|null $document_sync_status
 * @property bool|null $profile_sync_status
 * @property int|null $sponsor
 * @property string|null $blood_group
 * @property int|null $study_centre_group_id
 * @property int|null $reg_no_serial_no
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class AdmittedStudent extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_admitted_student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uon_prog_code', 'source_id', 'intake_code', 'student_category_id', 'gender'], 'required'],
            [['source_id', 'application_refno', 'intake_code', 'student_category_id', 'sponsor', 'study_centre_group_id', 'reg_no_serial_no'], 'default', 'value' => null],
            [['source_id', 'application_refno', 'intake_code', 'student_category_id', 'sponsor', 'study_centre_group_id', 'reg_no_serial_no'], 'integer'],
            [['doc_submission_status', 'document_sync_status', 'profile_sync_status'], 'boolean'],
            [['primary_email_verified_date', 'secondary_email_verified_date', 'password_changed_date', 'date_of_birth', 'last_update'], 'safe'],
            [['kcse_index_no', 'post_address', 'kuccps_prog_code', 'uon_prog_code', 'national_id', 'birth_cert_no', 'passport_no', 'service', 'userid'], 'string', 'max' => 20],
            [['kcse_year', 'post_code', 'primary_phone_country_code', 'alternative_phone_country_code'], 'string', 'max' => 10],
            [['primary_phone_no', 'alternative_phone_no', 'town', 'surname', 'nationality', 'ip_address'], 'string', 'max' => 50],
            [['primary_email', 'alternative_email', 'password', 'user_action'], 'string', 'max' => 100],
            [['admission_status', 'clearance_status', 'service_number'], 'string', 'max' => 30],
            [['primary_email_salt', 'secondary_email_salt'], 'string', 'max' => 255],
            [['other_names'], 'string', 'max' => 150],
            [['gender'], 'string', 'max' => 1],
            [['blood_group'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
            'reg_no_serial_no' => 'Reg no. serial no.',
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
            'document_sync_status' => 'Document Sync Status',
            'profile_sync_status' => 'Profile Sync Status',
            'sponsor' => 'Sponsor',
            'blood_group' => 'Blood Group',
            'study_centre_group_id' => 'Study center group id'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getIntake(): ActiveQuery
    {
        return $this->hasOne(Intake::class, ['intake_code' => 'intake_code']);
    }

    /**
     * @return ActiveQuery
     */
    public function getIntakeSource(): ActiveQuery
    {
        return $this->hasOne(IntakeSource::class, ['source_id' => 'source_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(StudentCategory::class, ['std_category_id' => 'student_category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgramme(): ActiveQuery
    {
        return $this->hasOne(Programme::class, ['prog_code' => 'uon_prog_code']);
    }

    public function getStudentGroup()
    {
        return $this->hasOne(StudentGroup::class, ['adm_refno' => 'adm_refno']);
    }  
}

