<?php

namespace app\modules\examManagement\models\search;

use yii\db\Query;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrCourse;
use app\models\CrProgCurrTimetable;
use app\models\OrgProgCurrSemesterGroup;
use app\models\CrProgrammeCurrLectureTimetable;

/**
 * OrgProgCurrSemesterGroupSearch represents the model behind the search form of `app\models\OrgProgCurrSemesterGroup`.
 */
class ClassListStudentSearch extends CrProgCurrTimetable
{
    public $curriculum;
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
            [[
                'prog_curriculum_id', 'acad_session_id', 'study_centre_id', 'study_group_id', 'semester_type_id', 'semester_code', 'academic_level_id'
            ], 'required'],
            [[
                'exam_date', 'progCurrCourse', 'progCurrSemGroup',
                'examMode', 'examVenue', 'programme_level_id',
                'semester_code', 'academic_level_id',

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

    private function getMarksheet(array $filters): string
    {
        $acadeSess = \app\modules\studentRegistration\models\AcademicSession::findOne($filters['acad_session_id']);
        $year = $acadeSess->acad_session_name;

        $progCurr = \app\modules\studentRegistration\models\ProgrammeCurriculum::findOne($filters['prog_curriculum_id']);
        $prog = \app\modules\studentRegistration\models\Programme::findOne($progCurr->prog_id);
        $progCode = $prog->prog_code;

        $acadLevel = \app\modules\studentRegistration\models\AcademicLevel::findOne($filters['academic_level_id']);
        $level = $acadLevel->academic_level;

        return $year . '_' . $progCode . '_' . $level . '_' . $filters['semester_code'] . '_' . $filters['study_group_id'];
    }


    private function populateFormValues($params)
    {
        foreach ($params as $key => $param) {
            if (array_key_exists($key, get_object_vars($this))) {
                $this->$key = $param;
            }
        }
    }


    private function orgData($data)
    {
        $finalData = [];
        foreach ($data as $row) {
            $finalData[] = ['course_id' => $row['course_id'], 'course_name' => $row['course_name']];
            // $finalData[] = ['timetable_id' => $row['timetable_id'], 'marksheet_id' => $row['marksheet_id'], 'course_work_mark' => $row['course_work_mark']];
            // $finalData[] = ['timetable_id' => $row['timetable_id'], 'marksheet_id' => $row['marksheet_id'], 'exam_mark' => $row['exam_mark']];
            // $finalData[] = ['timetable_id' => $row['timetable_id'], 'marksheet_id' => $row['marksheet_id'], 'final_mark' => $row['final_mark']];
        }
        return $finalData;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * 
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // $partialMarksheetId = $this->getMarksheet($params);
        $query = OrgProgCurrCourse::find()
            ->select([
                'org_prog_curr_semester_group.prog_curriculum_sem_group_id',
                'org_courses.course_code',
                // 'cr_prog_curr_timetable.timetable_id',
                'org_academic_levels.academic_level_name',
                'org_courses.course_name',
                'org_courses.course_id',
                'org_semester_code.semster_name',
                'org_prog_curr_semester.prog_curriculum_semester_id',
                'org_prog_curr_course.prog_curriculum_course_id',
                'org_prog_curr_course.level_of_study',
                'cr_prog_curr_timetable.timetable_id',
                'cr_prog_curr_timetable.mrksheet_id',
                'ex_marksheet.marksheet_id',
                'ex_marksheet.course_work_mark',
                'ex_marksheet.final_mark',
                'ex_marksheet.exam_mark',
                'cr_course_registration.registration_number'

                // 'org_prog_level.programme_level_name',
            ])
            ->joinWith(['timetable' => function (ActiveQuery $tmt) {
                $tmt->joinWith(['crCourseRegistrations' => function (ActiveQuery $crg) {
                    $crg->joinWith(['exMarksheets']);
                }]);
            }])
            ->joinWith(['course', 'semesterCode', 'academicLevels'])
            ->joinWith(['progCurriculum' => function (ActiveQuery $t) {
                $t->joinWith(['orgProgCurrSemesters' => function (ActiveQuery $q) {
                    $q->joinWith(['orgSemesterType']);
                    $q->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
                        $a->joinWith('acadSession');
                    }]);
                    $q->joinWith(['orgProgCurrSemesterGroups' => function (ActiveQuery $r) {
                        $r->joinWith(['studyCentreGroup' => function (ActiveQuery $k) {
                            $k->joinWith(['studyCentre', 'studyGroup']);
                        }]);
                    }]);
                }]);
            }]);

        // $query = CrProgCurrTimetable::find()
        //     ->select([
        //         'org_courses.course_id',
        //         'org_courses.course_code',
        //         'org_courses.course_name',
        //         'cr_prog_curr_timetable.timetable_id',
        //         'org_academic_levels.academic_level_name',
        //         'org_semester_code.semster_name',
        //     ])
        //     ->joinWith(['orgProgCurrCourse' => function (ActiveQuery $r) {
        //         $r->joinWith(['course', 'semesterCode', 'academicLevels']);
        //     }])
        //     ->joinWith(['progCurriculumSemGroup' => function ($t) {
        //         $t->joinWith(['progCurriculumSemester' => function (ActiveQuery $r) {
        //             $r->joinWith(['progCurriculum', 'orgSemesterType']);
        //             $r->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
        //                 $a->joinWith('acadSession');
        //             }]);
        //         }]);
        //         $t->joinWith(['studyCentreGroup' => function (ActiveQuery $k) {
        //             $k->joinWith(['studyCentre', 'studyGroup']);
        //         }]);
        //     }])

        // ->where(['like', 'mrksheet_id', $partialMarksheetId . '%', false])
        $query->andWhere([
            'org_programme_curriculum.prog_curriculum_id' => $params['prog_curriculum_id'],

            // 'org_academic_session.acad_session_id' => $params['acad_session_id'],
            // // 'org_semester_type.semester_type_id' => $params['semester_type_id'],
            // 'org_semester_code.semester_code' => $params['semester_code'],
            // // 'org_study_centre.study_centre_id' => $params['study_centre_id'],
            // 'org_study_group.study_group_id' => $params['study_group_id'],
            // // 'org_prog_level.programme_level_id' => $params['programme_level_id'],
            // 'org_academic_levels.academic_level_id' => $params['academic_level_id'],
            'org_prog_curr_course.status' => 'ACTIVE',
        ]);

        $query->andWhere(
            ['not', ['cr_prog_curr_timetable.timetable_id' => null]],
        );
        $query->andWhere(
            ['not', ['ex_marksheet.marksheet_id' => null]],
        );
        $query->orderBy([
            'org_academic_levels.academic_level' => SORT_ASC,
            'org_semester_code.semester_code' => SORT_ASC,
            'org_courses.course_code' => SORT_ASC,
        ])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false

        ]);


        $this->populateFormValues($params);

        if (!$this->validate()) {
            // uncomme  nt the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
