<?php

namespace app\modules\courseRegistration\models\search;

use app\models\CrCourseRegistration;
use app\models\CrProgrammeCurrLectureTimetable;
use app\models\CrProgCurrTimetable;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrSemesterGroup;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * OrgProgCurrSemesterGroupSearch represents the model behind the search form of `app\models\OrgProgCurrSemesterGroup`.
 */
class ClassListViewSearch extends CrProgCurrTimetable
{
    public $course_id;
    public $studyGroup;

    public $programmeLevel;
    public $prog_curriculum_id;
    public $progCurrCourse;
    public $progCurrSemGroup;
    public $examMode;
    public $examVenue;
    public $acad_session_id;
    public $study_group_id;
    public $study_centre_id;
    public $semester_type_id;
    public $semester_code;
    public $programme_level_id;
    public $academic_level_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['prog_curriculum_sem_group_id', 'prog_curriculum_semester_id', 'study_centre_group_id', 'programme_level'], 'integer'],
            [[
                'exam_date', 'progCurrCourse', 'progCurrSemGroup',
                'examMode', 'examVenue', 'programme_level_id',
                'prog_curriculum_id', 'semester_code', 'academic_level_id',
                'acad_session_id', 'study_group_id', 'study_centre_id',
                'semester_type_id', 'programmeLevel', 'curriculum', 'studyGroup'
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = CrCourseRegistration::find()
            ->select([
                'cr_course_registration.student_course_reg_id',
                'cr_course_registration.timetable_id',
                'sm_admitted_student.adm_refno',
                'sm_admitted_student.surname',
                'sm_admitted_student.other_names',
                'sm_admitted_student.primary_email',
                'sm_admitted_student.alternative_email',
                'sm_admitted_student.primary_phone_no',
                'sm_admitted_student.alternative_phone_no',

            ])
            ->innerJoinWith(['smStudentSemSessionProgress' => function (ActiveQuery $r) {
                $r->innerJoinWith(['academicProgress' => function (ActiveQuery $ap) {
                    $ap->innerJoinWith(['studentProgCurriculum' => function (ActiveQuery $str) {
                        $str->innerJoinWith('admRefno');
                    }]);
                }]);
            }])
            ->where([
                'cr_course_registration.timetable_id' => $params['timetable_id']
            ])
            ->asArray();



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 50,
            ],

        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
