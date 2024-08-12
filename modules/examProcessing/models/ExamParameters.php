<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace app\modules\examProcessing\models;

use app\models\OrgAcademicLevels;
use app\models\OrgAcademicSession;
use app\models\OrgProgrammes;
use app\models\ProgCurrLevelRequirement;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * @property int $programme_id
 * @property string $academic_year
 * @property int $academic_level
 * @property int $student_group
 * @property int $min_total_courses
 * @property int $max_total_courses
 * @property string $start_reg_no
 * @property string $end_reg_no
 */
class ExamParameters extends Model
{

    public $programme_id = 16;
    public $academic_year = 81;
    public $academic_level = -1;
    public $student_group = -1;
    public $min_total_courses = 1;
    public $max_total_courses = 60;
    public $start_reg_no = '00000000';
    public $end_reg_no = 'ZZZZZZZZ';

    public function rules(): array
    {
        return [
            [
                [
                    'programme_id', 'academic_year', 'academic_level', 'student_group',
                    'min_total_courses', 'max_total_courses', 'start_reg_no', 'end_reg_no'
                ],
                'required'
            ],

            [['dob', 'registration_date'], 'safe'],
            [['sponsor'], 'default', 'value' => null],
            [['min_total_courses', 'max_total_courses'], 'integer'],
            [['student_number', 'passport_no', 'service_number'], 'string', 'max' => 20],
            [['surname'], 'string', 'max' => 50],
            [['other_names'], 'string', 'max' => 100],
            [['gender'], 'string', 'max' => 1],
            [['country_code'], 'string', 'max' => 3],
            [['id_no'], 'string', 'max' => 10],
            [['blood_group'], 'string', 'max' => 5],
//            [['country_code'], 'exist', 'skipOnError' => true, 'targetClass' => OrgCountry::class, 'targetAttribute' => ['country_code' => 'country_code']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'programme_id' => 'Programme',
            'academic_year' => 'Academic year',
            'academic_level' => 'Year of study',
        ];
    }

    /**
     * @return array
     */
    public function fetchAcademicProgrammes(): array
    {
        $programmes = OrgProgrammes::find()
            ->where(['status' => 'ACTIVE'])
            ->orderBy(['prog_code' => SORT_ASC])
            ->asArray()
            ->all();


        $data = [];
        foreach ($programmes as $key => $programme) {
            $displayProgramme = $programme['prog_code'] . '-' . $programme['prog_full_name'];
            $data[$programme['prog_id']] = $displayProgramme;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function fetchAcademicYear(): array
    {
        $academicYears = OrgAcademicSession::find()
            ->orderBy(['acad_session_name' => SORT_DESC])
            ->asArray()
            ->all();


        $data = [];
        foreach ($academicYears as $key => $academicYear) {
            $academicYearDisplayName = $academicYear['acad_session_name'] . '(' . $academicYear['acad_session_desc'] . ')';
            $data[$academicYear['acad_session_id']] = $academicYearDisplayName;
        }
        return $data;
    }

    /**
     * @return array
     */
    public function fetchAcademicLevels(): array
    {
        $academicLevels = OrgAcademicLevels::find()
            ->orderBy(['academic_level' => SORT_ASC])
            ->asArray()
            ->all();

        $data[-1] = 'All Years';
        foreach ($academicLevels as $key => $academicLevel) {
            $data[$academicLevel['academic_level_id']] = $academicLevel['academic_level_name'];
        }
        return $data;
    }

    public function fetchStudentGroups(): array
    {
        return [
            -1 => 'All Student Groups'
        ];
    }

    /**
     * @param ExamParameters $model
     * @return array|\yii\db\DataReader
     * @throws Exception
     */
    public function fetchStudentProgress(ExamParameters $model): \yii\db\DataReader|array
    {
        $query = <<<SQL
SELECT
	sm_academic_progress.academic_progress_id,
	sm_academic_progress.acad_session_id,
	sm_academic_progress.academic_level_id,
	sm_academic_progress.student_prog_curriculum_id,
	sm_academic_progress.progress_status_id,
	sm_academic_progress.current_status,
	sm_student_programme_curriculum.student_id,
	sm_student_programme_curriculum.registration_number,
	sm_student_programme_curriculum.prog_curriculum_id,
	org_programme_curriculum.prog_id,
	org_programme_curriculum.prog_curriculum_desc,
	org_programme_curriculum.duration,
	org_programme_curriculum.pass_mark,
	org_programme_curriculum.annual_semesters,
	org_programme_curriculum.max_units_per_semester,
	org_programme_curriculum.average_type,
	org_programme_curriculum.award_rounding,
	org_programme_curriculum.grading_system_id
FROM
	smis.sm_academic_progress
	INNER JOIN smis.sm_student_programme_curriculum ON sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN smis.org_programme_curriculum ON sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
SQL;

        $query .= <<<SQL
        WHERE sm_academic_progress.acad_session_id = '$model->academic_year'
        AND org_programme_curriculum.prog_id = $model->programme_id
SQL;

        if ($model->academic_level > 0) {
            $query .= <<<SQL
        AND sm_academic_progress.academic_level_id = $model->academic_level
SQL;
        }


        $command = \Yii::$app->db->createCommand($query);
        return $command->queryAll();
    }

    /**
     * @param int $progCurriculumId
     * @param $academicLevel
     * @param string $studentOption
     * @return array|\yii\db\ActiveRecord|null
     */
    public function fetchProgLevelReq(int $progCurriculumId, $academicLevel, string $studentOption = 'STUDENT_OPTION'): array|\yii\db\ActiveRecord|null
    {
        return ProgCurrLevelRequirement::find()
            ->where([
                'prog_curriculum_id' => $progCurriculumId,
                'prog_study_level' => $academicLevel
            ])
            ->one();
    }
}
