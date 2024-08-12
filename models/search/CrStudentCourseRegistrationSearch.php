<?php
/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-02-06 14:03:42 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-02-06 14:14:55
 * @Description: file:///home/wahomez/Github/smis/models/search/CrStudentCourseRegistrationSearch.php
 */


namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CrCourseRegistration;
use yii\db\ActiveQuery;

/**
 * CrCourseRegistrationSearch represents the model behind the search form of `app\models\CrCourseRegistration`.
 */
class CrStudentCourseRegistrationSearch extends CrCourseRegistration
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'sm_student.student_number',
            'sm_student.surname',
            'cr_course_registration.registration_number',
            'org_courses.course_name',
            'cr_course_registration.student_course_reg_id',
            'org_academic_session.acad_session_name',
            'org_prog_curr_course.semester',
            'cr_course_reg_type.course_reg_type_code'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return
            [
                [[
                    'student_number',
                    'surname',
                    'registration_number',
                    'course_name',
                    'acad_session_name',
                    'semester',
                    'course_reg_type_code'
                ], 'safe']
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
    public function search($params, $regNumber)
    {
        $query = CrCourseRegistration::find()
            ->select([
                'cr_course_registration.registration_number',
                'cr_course_registration.student_course_reg_id',
                'sm_student.student_number',
                'sm_student.surname',
                'org_courses.course_name',
                'org_academic_session.acad_session_name',
                'org_prog_curr_course.semester',
                'cr_course_reg_type.course_reg_type_code'
            ])
            ->joinWith(['student'])
            ->joinWith(['courseRegistrationType'])
            ->joinWith(['timetable' => function (ActiveQuery $t) {
                $t->joinWith(['orgProgCurrCourse' => function (ActiveQuery $opcc) {
                    $opcc->joinWith('course');
                }]);
                $t->joinWith(['progCurriculumSemGroup' => function (ActiveQuery $pcsg) {
                    $pcsg->joinWith(['progCurriculumSemester' => function (ActiveQuery $pcs) {
                        $pcs->joinWith(['acadSessionSemester' => function (ActiveQuery $ass) {
                            $ass->joinWith('acadSession');
                        }]);
                    }]);
                }]);
            }]);


        $this->load($params);

        $query->andFilterWhere(
            ['=', 'cr_course_registration.registration_number', $regNumber]
        );
        $query->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        return $dataProvider;
    }
}
