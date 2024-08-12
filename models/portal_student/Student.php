<?php

namespace app\models\portal_student;

use Yii;
use yii\db\ActiveQuery;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "student".
 *
 * @property int $student_id
 * @property string $student_number
 * @property string $surname
 * @property string $other_names
 * @property string $gender
 * @property string $country_code
 * @property string $dob
 * @property string|null $id_no
 * @property string|null $passport_no
 * @property string|null $service_number
 * @property string|null $blood_group
 * @property int|null $sponsor
 * @property string|null $registration_date
 *
 * @property OrgCountry $countryCode
 * @property StudentMentor[] $studentMentors
 * @property SmStudentSponsor $sponsorList
 * @property StudentProgrammeCurriculum[] $studentProgrammeCurriculums
 */
class Student extends \yii\db\ActiveRecord
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
        return 'smisportal.sm_student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_number', 'surname', 'other_names', 'gender', 'country_code', 'dob'], 'required'],
            [['dob', 'registration_date'], 'safe'],
            [['sponsor'], 'default', 'value' => null],
            [['sponsor'], 'integer'],
            [['student_number', 'passport_no', 'service_number'], 'string', 'max' => 20],
            [['surname'], 'string', 'max' => 50],
            [['other_names'], 'string', 'max' => 100],
            [['gender'], 'string', 'max' => 1],
            [['country_code'], 'string', 'max' => 3],
            [['id_no'], 'string', 'max' => 10],
            [['blood_group'], 'string', 'max' => 5],
            [['country_code'], 'exist', 'skipOnError' => true, 'targetClass' => OrgCountry::class, 'targetAttribute' => ['country_code' => 'country_code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_id' => 'Student ID',
            'student_number' => 'Student Number',
            'surname' => 'Surname',
            'other_names' => 'Other Names',
            'gender' => 'Gender',
            'country_code' => 'Country Code',
            'dob' => 'Dob',
            'id_no' => 'Id No',
            'passport_no' => 'Passport No',
            'service_number' => 'Service Number',
            'blood_group' => 'Blood Group',
            'sponsor' => 'Sponsor',
            'registration_date' => 'Registration Date',
        ];
    }

    /**
     * Gets query for [[CountryCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountryCode()
    {
        return $this->hasOne(OrgCountry::class, ['country_code' => 'country_code']);
    }

    /**
     * Gets query for [[StudentMentors]].
     *
     * @return \yii\db\ActiveQuery
     */
    //    public function getStudentMentors()
    //    {
    //        return $this->hasMany(StudentMentor::class, ['student_id' => 'student_id']);
    //    }

    /**
     * @return ActiveQuery
     */
    public function getSponsorList()
    {
        return $this->hasOne(SmStudentSponsor::class, ['sponsor_id' => 'sponsor']);
    }

    /**
     * Gets query for [[StudentProgrammeCurriculums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentProgrammeCurriculums()
    {
        return $this->hasMany(StudentProgrammeCurriculum::class, ['student_id' => 'student_id']);
    }

    public function getFullNames()
    {
        return $this->surname . ' ' . $this->other_names;
    }

    /**
     * Returns Photo as a Base64 string
     * @return string
     */
    public function avatar(): string
    {
        $id = str_replace('/', '', $this->student_number);
        if (!empty($extra_path)) {
            $extra_path = $extra_path . '/';
        }
        $dir = Yii::getAlias('@photos') . '/students/';
        $img1 = glob($dir . $id . '.*');
        if (empty($img1)) {
            $img = 0;
        } else {
            $img1 = $img1[0];
            $img = (object)pathinfo($img1);
            $type = $img->extension;

            $data = file_get_contents($img1);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            $img = $base64;
        }

        return $img ?: Yii::$app->getHomeUrl() . 'img/default-avatar.jpg';
    }
}
