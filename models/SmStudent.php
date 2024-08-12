<?php

namespace app\models;

use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student".
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
 * @property SmNameChange[] $smNameChanges
 * @property SmStudentProgrammeCurriculum[] $smStudentProgrammeCurriculums
 * @property SmWithdrawalRequest[] $smWithdrawalRequests
 */
class SmStudent extends Student
{

    use UserTracking;


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
     * Gets query for [[SmNameChanges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmNameChanges()
    {
        return $this->hasMany(SmNameChange::class, ['student_id' => 'student_id']);
    }

    /**
     * Gets query for [[SmStudentProgrammeCurriculums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentProgrammeCurriculums()
    {
        return $this->hasMany(SmStudentProgrammeCurriculum::class, ['student_id' => 'student_id']);
    }

    /**
     * Gets query for [[SmWithdrawalRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmWithdrawalRequests()
    {
        return $this->hasMany(SmWithdrawalRequest::class, ['student_id' => 'student_id']);
    }

    /**
     * Gets query for [[StudentCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return SmStudentProgrammeCurriculum::findOne($this->student_id)->getStudentCategory();
    }
}
