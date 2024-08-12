<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_prog_curr_semester_group".
 *
 * @property int $prog_curriculum_sem_group_id
 * @property int $prog_curriculum_semester_id
 * @property int $study_centre_group_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $registration_deadline
 * @property string|null $display_date
 * @property int $programme_level
 * @property string $status
 *
 * @property CrProgCurrTimetable[] $crProgCurrTimetables
 * @property OrgProgCurrSemester $progCurriculumSemester
 * @property OrgProgLevel $programmeLevel
 * @property OrgStudyCentreGroup $studyCentreGroup
 */
class OrgProgCurrSemesterGroup extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_prog_curr_semester_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_semester_id', 'study_centre_group_id', 'start_date', 'programme_level'], 'required'],
            [['prog_curriculum_semester_id', 'study_centre_group_id', 'programme_level'], 'default', 'value' => null],
            [['prog_curriculum_semester_id', 'study_centre_group_id', 'programme_level'], 'integer'],
            [['start_date', 'end_date', 'registration_deadline', 'display_date'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['prog_curriculum_semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrSemester::className(), 'targetAttribute' => ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']],
            [['programme_level'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgLevel::className(), 'targetAttribute' => ['programme_level' => 'programme_level_id']],
            [['study_centre_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgStudyCentreGroup::className(), 'targetAttribute' => ['study_centre_group_id' => 'study_centre_group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curriculum_sem_group_id' => 'Prog Curriculum Sem Group ID',
            'prog_curriculum_semester_id' => 'Prog Curriculum Semester ID',
            'study_centre_group_id' => 'Study Centre Group ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'registration_deadline' => 'Registration Deadline',
            'display_date' => 'Display Date',
            'programme_level' => 'Programme Level',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[CrProgCurrTimetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCrProgCurrTimetables()
    {
        return $this->hasMany(CrProgCurrTimetable::className(), ['prog_curriculum_sem_group_id' => 'prog_curriculum_sem_group_id']);
    }

    /**
     * Gets query for [[ProgCurriculumSemester]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurriculumSemester()
    {
        return $this->hasOne(OrgProgCurrSemester::className(), ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']);
    }

    /**
     * Gets query for [[ProgrammeLevel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeLevel()
    {
        return $this->hasOne(OrgProgLevel::className(), ['programme_level_id' => 'programme_level']);
    }

    /**
     * Gets query for [[StudyCentreGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudyCentreGroup()
    {
        return $this->hasOne(OrgStudyCentreGroup::className(), ['study_centre_group_id' => 'study_centre_group_id']);
    }
}
