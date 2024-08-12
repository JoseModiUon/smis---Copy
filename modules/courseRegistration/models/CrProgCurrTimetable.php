<?php


namespace app\modules\courseRegistration\models;


use Yii;
use yii\db\Query;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "cr_prog_curr_timetable".
 *
 * @property int $timetable_id
 * @property int $prog_curriculum_course_id
 * @property int $prog_curriculum_sem_group_id
 * @property string|null $exam_date
 * @property int|null $exam_venue
 * @property int $exam_mode
 * @property int $publish_status
 *
 * @property CrCourseRegistration[] $crCourseRegistrations
 * @property CrProgrammeCurrLectureTimetable[] $crProgrammeCurrLectureTimetables
 * @property ExMode $examMode
 * @property OrgProgCurrSemesterGroup $progCurriculumSemGroup
 */
class CrProgCurrTimetable extends \app\extended\BaseModel
{
    use UserTracking;

    public $prog_curriculum_id;
    /**  
     * {@inheritdoc}
     */

    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function tableName()
    {
        return 'smisportal.cr_prog_curr_timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_mode'], 'required'],
            [['prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'default', 'value' => null],
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'integer'],
            [['exam_date', 'prog_curriculum_id'], 'safe'],
            [['timetable_id'], 'unique'],
            // [['exam_mode'], 'exist', 'skipOnError' => true, 'targetClass' => ExMode::class, 'targetAttribute' => ['exam_mode' => 'exam_mode_id']],
            //[['prog_curriculum_sem_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrSemesterGroup::class, 'targetAttribute' => ['prog_curriculum_sem_group_id' => 'prog_curriculum_sem_group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'timetable_id' => 'Timetable ID',
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'prog_curriculum_sem_group_id' => 'Prog Curriculum Sem Group ID',
            'exam_date' => 'Exam Date',
            'exam_venue' => 'Exam Venue',
            'exam_mode' => 'Exam Mode',
        ];
    }

    /**
     * Gets query for [[CrCourseRegistrations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCrCourseRegistrations()
    {
        return $this->hasMany(CrCourseRegistration::class, ['timetable_id' => 'timetable_id']);
    }

    /**
     * Gets query for [[CrProgrammeCurrLectureTimetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCrProgrammeCurrLectureTimetables()
    {
        return $this->hasMany(CrProgrammeCurrLectureTimetable::class, ['timetable_id' => 'timetable_id']);
    }

    /**
     * Gets query for [[ExamMode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamMode()
    {
        return $this->hasOne(ExMode::class, ['exam_mode_id' => 'exam_mode']);
    }

    /**
     * Gets query for [[ProgCurriculumSemGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurriculumSemGroup()
    {
        return $this->hasOne(OrgProgCurrSemesterGroup::class, ['prog_curriculum_sem_group_id' => 'prog_curriculum_sem_group_id']);
    }



    /**
     * Gets query for [[ExamMode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgCurrCourse()
    {
        return $this->hasOne(OrgProgCurrCourse::class, ['prog_curriculum_course_id' => 'prog_curriculum_course_id']);
    }
    public function CHECKTIMETABLEEXISTS($prog_curriculum_course_id, $prog_curriculum_sem_group_id)
    {
        $db = self::getDb();
        $sql = "select distinct cr_programme_curr_lecture_timetable.timetable_id from smis.cr_programme_curr_lecture_timetable where 
            cr_programme_curr_lecture_timetable.timetable_id in 
            (select cr_prog_curr_timetable.timetable_id  from smis.cr_prog_curr_timetable
                    where prog_curriculum_course_id =1061 
                and prog_curriculum_sem_group_id =21)";
        $model = $db->createCommand($sql);
        $data = $model->queryOne();
        return $data;
    }
}
