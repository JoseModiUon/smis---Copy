<?php

namespace app\modules\feesManagement\models;
use app\modules\feesManagement\models\AcademicProgress;
use app\modules\feesManagement\models\AdmittedStudent as ModelsAdmittedStudent;
use app\modules\studentRegistration\models\AdmittedStudent;
use app\modules\studentRegistration\models\StudentCategory;
use app\modules\studentRegistration\models\StudentStatus;
use Yii;

/**
 * This is the model class for table "smis.sm_student_programme_curriculum".
 *
 * @property int $student_prog_curriculum_id
 * @property int $student_id
 * @property string $registration_number
 * @property int $prog_curriculum_id
 * @property int $student_category_id
 * @property int $adm_refno
 * @property int $status_id
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class StudentProgrammeCurriculum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_programme_curriculum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'registration_number', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'required'],
            [['student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'default', 'value' => null],
            [['student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'integer'],
            [['last_update'], 'safe'],
            [['registration_number', 'userid'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 50],
            [['user_action'], 'string', 'max' => 10],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgrammeCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
            [['adm_refno'], 'exist', 'skipOnError' => true, 'targetClass' => AdmittedStudent::class, 'targetAttribute' => ['adm_refno' => 'adm_refno']],
            [['student_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentCategory::class, 'targetAttribute' => ['student_category_id' => 'std_category_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentStatus::class, 'targetAttribute' => ['status_id' => 'status_id']],
            [['adm_refno'], 'exist', 'skipOnError' => true, 'targetClass' => AdmittedStudent::class, 'targetAttribute' => ['adm_refno' => 'adm_refno']],
            [['student_prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicProgress::class, 'targetAttribute' => ['student_prog_curriculum_id' => 'student_prog_curriculum_id']],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'student_id' => 'Student ID',
            'registration_number' => 'Registration Number',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'student_category_id' => 'Student Category ID',
            'adm_refno' => 'Adm Refno',
            'status_id' => 'Status ID',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }

    public function getFullName($registrationNumber)
    {
        $fullName = Yii::$app->db->createCommand(
            "
            SELECT 
            CONCAT(sas.other_names, ' ', sas.surname) as full_names 
            FROM SMIS.sm_student_programme_curriculum sspc 
            INNER JOIN smis.sm_admitted_student sas on sspc.adm_refno = sas.adm_refno
            WHERE sspc.registration_number = :regno
            GROUP BY sspc.adm_refno, sas.other_names, sas.surname
            "
        )
            ->bindValue(':regno', strval($registrationNumber))
            ->queryScalar();
        return $fullName;
    }


    public function getStudentProgCurrId($registrationNumber)
    {
        $id = Yii::$app->db->createCommand(
            "
            SELECT 
            sspc.student_prog_curriculum_id
            FROM SMIS.sm_student_programme_curriculum sspc 
            INNER JOIN smis.sm_admitted_student sas on sspc.adm_refno = sas.adm_refno
            WHERE sspc.registration_number = :regno
            GROUP BY sspc.student_prog_curriculum_id
            "
        )
            ->bindValue(':regno', strval($registrationNumber))
            ->queryScalar();
        return $id;
    }

    public function getStatus($registrationNumber)
    {
        $status = Yii::$app->db->createCommand(
            "
                SELECT 
                st.status
                FROM SMIS.sm_student_programme_curriculum sspc 
                INNER JOIN smis.sm_admitted_student sas on sspc.adm_refno = sas.adm_refno
                INNER JOIN smis.sm_student_status st on st.status_id = sspc.status_id
                WHERE sspc.registration_number = :regno
                GROUP BY sspc.adm_refno, sas.other_names, sas.surname, sspc.status_id, st.status
                "
        )
            ->bindValue(':regno', strval($registrationNumber))
            ->queryScalar();

        return $status;
    }

    public function getStatusId($registrationNumber)
    {
        $status_id = Yii::$app->db->createCommand(
            "
                SELECT 
                sspc.status_id
                FROM SMIS.sm_student_programme_curriculum sspc 
                INNER JOIN smis.sm_admitted_student sas on sspc.adm_refno = sas.adm_refno
                INNER JOIN smis.sm_student_status st on st.status_id = sspc.status_id
                WHERE sspc.registration_number = :regno
                GROUP BY sspc.adm_refno, sas.other_names, sas.surname, sspc.status_id, st.status
                "
        )
            ->bindValue(':regno', strval($registrationNumber))
            ->queryAll();

        return $status_id;
    }


    public function getModule($registrationNumber)
    {
        $module = Yii::$app->db->createCommand(
            "
                    SELECT 
                    curr.prog_curriculum_desc
                    FROM SMIS.sm_student_programme_curriculum sspc 
                    INNER JOIN smis.sm_admitted_student sas on sspc.adm_refno = sas.adm_refno
                    INNER JOIN smis.org_programme_curriculum curr on curr.prog_curriculum_id = sspc.prog_curriculum_id
                    WHERE sspc.registration_number = :regno
                    GROUP BY sspc.adm_refno, sas.other_names, sas.surname, sspc.status_id, curr.prog_curriculum_desc
                    "
        )
            ->bindValue(':regno', strval($registrationNumber))
            ->queryScalar();
        return $module;
    }

    public function getProgCurricullumId()
    {
        $curr_id = Yii::$app->db->createCommand('');

        return $curr_id;
    }



    public function getAcadProgressGrid($registrationNumber)
    {
        $acadProgress = Yii::$app->db->createCommand(
            "
        SELECT sap.academic_progress_id, 
               oas.acad_session_name, 
               oas.acad_session_id,
               oal.academic_level_name, 
               sspc.registration_number, 
               saps.progress_status_name, 
               sss.status,
               sspc.student_prog_curriculum_id,
               sap.current_status,
               ssssp.sem_progress_number,
               oal.academic_level
        FROM smis.sm_academic_progress sap
        INNER JOIN smis.org_academic_session oas ON oas.acad_session_id = sap.acad_session_id 
        INNER JOIN smis.org_academic_levels oal ON oal.academic_level_id = sap.academic_level_id
        INNER JOIN smis.sm_student_programme_curriculum sspc ON sspc.student_prog_curriculum_id = sap.student_prog_curriculum_id 
        INNER JOIN smis.sm_academic_progress_status saps ON saps.progress_status_id = sap.progress_status_id 
        INNER JOIN smis.sm_student_status sss ON sap.current_status = sss.status_id 
        INNER JOIN smis.sm_student_status sss2 on sss2.status_id = sap.current_status 
        INNER JOIN smis.sm_student_sem_session_progress ssssp on ssssp.academic_progress_id = sap.academic_progress_id 
        WHERE sspc.registration_number = :regno
        ORDER BY oas.acad_session_name;
                "
        )
            ->bindValue(':regno', strval($registrationNumber))
            ->queryAll();

        return $acadProgress;
    }

    public function getSemesterProgressGrid($registrationNumber)
    {
        $semesterProgress = Yii::$app->db->createCommand(
            "
        SELECT 
        ssp.student_semester_session_id,
        sspc.registration_number,
        oass.acad_session_semester_desc,
        saps.progress_status_name,
        oass.semester_code,
        sap.academic_level_id,
        ssp.semester_progress,
        ssp.student_semester_session_id,
        sspc.student_prog_curriculum_id,
        sms.status_name,
        oas.acad_session_name, 
        ssp.sem_progress_number,
        oass.semester_code,
        osc.semster_name,
        ssp.academic_progress_id,
        ssp.prog_curriculum_semester_id
        FROM smis.sm_student_sem_session_progress ssp
        INNER join smis.org_academic_session_semester oass on ssp.semester_progress = oass.acad_session_semester_id
        INNER join smis.org_semester_code osc on osc.semester_code = oass.semester_code 
        INNER join smis.sm_academic_progress sap on sap.academic_progress_id = ssp.academic_progress_id 
        INNER JOIN smis.sm_academic_progress_status saps ON saps.progress_status_id = sap.progress_status_id
        INNER join smis.org_academic_session oas ON oas.acad_session_id = sap.acad_session_id 
        INNER join smis.sm_student_programme_curriculum sspc ON sspc.student_prog_curriculum_id = sap.student_prog_curriculum_id  
        INNER join smis.org_academic_levels oal on sap.academic_level_id = oal.academic_level_id 
        INNER join smis.sm_student_semester_session_status sms on ssp.rep_status_id = sms.status_id
        where sspc.registration_number = :regno
        group by
        ssp.student_semester_session_id,
        sspc.registration_number,
        oass.acad_session_semester_desc,
        saps.progress_status_name,
        oass.semester_code,
        sap.academic_level_id,
        ssp.semester_progress,
        ssp.student_semester_session_id,
        sspc.student_prog_curriculum_id,
        sms.status_name,
        oas.acad_session_name, 
        ssp.sem_progress_number,
        oass.semester_code,
        osc.semster_name ,
        ssp.academic_progress_id,
        ssp.prog_curriculum_semester_id
        order by ssp.sem_progress_number asc
        ;
                    "
        )
            ->bindValue(':regno', strval($registrationNumber))
            ->queryAll();


        return $semesterProgress;
    }

    public function getStudentAcademicProgressDetailed($registrationNumber)
    {
        $progress = Yii::$app->db->createCommand(
            "
            select 
        oas.acad_session_name, 
       oal.academic_level,
       osc.semster_name,
       ssp.sem_progress_number,
       saps.progress_status_name,
       ssp.registration_date,
       sms.status_name,
       osc2.study_centre_name,
       osg.study_group_name
        FROM smis.sm_student_sem_session_progress ssp
        inner join smis.org_prog_curr_semester_group opcsg on opcsg.prog_curriculum_sem_group_id = ssp.prog_curriculum_semester_id
        inner join smis.org_study_centre_group oscg on oscg.study_centre_group_id = opcsg.study_centre_group_id
        inner join smis.org_study_centre osc2 on osc2.study_centre_id = oscg.study_centre_id 
        inner join smis.org_study_group osg on osg.study_group_id = oscg.study_group_id 
        inner join smis.org_academic_session_semester oass on ssp.semester_progress = oass.acad_session_semester_id
        inner join smis.org_semester_code osc on osc.semester_code = oass.semester_code 
        inner join smis.sm_academic_progress sap on sap.academic_progress_id = ssp.academic_progress_id 
        inner JOIN smis.sm_academic_progress_status saps ON saps.progress_status_id = sap.progress_status_id
        inner join smis.org_academic_session oas ON oas.acad_session_id = sap.acad_session_id 
        inner join smis.sm_student_programme_curriculum sspc ON sspc.student_prog_curriculum_id = sap.student_prog_curriculum_id  
        inner join smis.org_academic_levels oal on sap.academic_level_id = oal.academic_level_id 
        inner join smis.sm_student_semester_session_status sms on ssp.rep_status_id = sms.status_id
        where sspc.registration_number = :regno
        group by
          oas.acad_session_name, 
       oal.academic_level,
       osc.semster_name,
       ssp.sem_progress_number,
       saps.progress_status_name,
       ssp.registration_date,
       sms.status_name,
       osc2.study_centre_name,
       osg.study_group_name
            "
        )->bindValue(':regno', strval($registrationNumber))
        ->queryAll();;
        return $progress;
    }

    public function getAcademicProgress()
    {
        return $this->hasOne(AcademicProgress::class, ['student_prog_curriculum_id' => 'student_prog_curriculum_id']);
    }
    public function getProgrammeCurriculum()
    {
        return $this->hasOne(ProgrammeCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }
    public function getAdmittedStudent()
    {
        return $this->hasOne(AdmittedStudent::class, ['adm_refno' => 'adm_refno']);
    }
}
