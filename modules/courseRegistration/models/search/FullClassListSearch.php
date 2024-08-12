<?php

namespace app\modules\courseRegistration\models\search;

use app\models\CrProgCurrTimetable as ModelsCrProgCurrTimetable;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\courseRegistration\models\CrProgCurrTimetable;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\web\ServerErrorHttpException;

/**
 * FullClassListSearch represents the model behind the search form of `app\modules\courseRegistration\models\CrProgCurrTimetable`.
 */
class FullClassListSearch extends CrProgCurrTimetable
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'course_name',
            'course_code',
            'acad_session_name',
            'study_centre_name',
            'study_group_name',
            'semester_type',
            'semster_name',
            'programme_level',
            'academic_level_name'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return 
        [
            [['course_name','course_code','acad_session_name','study_centre_name','study_group_name','semester_type','semster_name',
            'programme_level', 'academic_level_name'], 'safe']
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

    public function search($params) {

        $query = ModelsCrProgCurrTimetable::find()
        ->select([
            'org_courses.course_id',
            'org_courses.course_code',
            'org_courses.course_name',
            'cr_prog_curr_timetable.timetable_id',
            'org_academic_levels.academic_level_name',
            'org_semester_code.semster_name',
            'org_semester_type.semester_type',
            'org_semester_type.semester_type_id',
            'org_academic_session.acad_session_name',
            'org_academic_session.acad_session_id',
            'org_study_centre.study_centre_name',
            'org_study_group.study_group_name',
            'org_study_group.study_group_id',
            'org_programme_curriculum.prog_curriculum_id',
            'org_semester_code.semester_code',
        ])
        ->joinWith(['orgProgCurrCourse' => function (ActiveQuery $r) {
            $r->joinWith(['course', 'semesterCode', 'academicLevels']);
        }])
        ->joinWith(['progCurriculumSemGroup' => function ($t) {
            $t->joinWith(['progCurriculumSemester' => function (ActiveQuery $r) {
                $r->joinWith(['progCurriculum', 'orgSemesterType']);
                $r->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
                    $a->joinWith('acadSession');
                }]);
            }]);
            $t->joinWith(['studyCentreGroup' => function (ActiveQuery $k) {
                $k->joinWith(['studyCentre', 'studyGroup']);
            }]);
        }]);

        $query->asArray();

        $dataProvider =  new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomme  nt the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



        $query->andFilterWhere(['ilike', 'org_courses.course_name', $this->getAttribute('course_name')]);
        $query->andFilterWhere(['ilike', 'org_courses.course_code', $this->getAttribute('course_code')]);
        $query->andFilterWhere(['like', 'org_academic_session.acad_session_name', $this->getAttribute('acad_session_name')]);
        $query->andFilterWhere(['like', 'org_academic_session.academic_level_name', $this->getAttribute('academic_level_name')]);
        $query->andFilterWhere(['like', 'org_study_centre.study_centre_name', $this->getAttribute('study_centre_name')]);
        $query->andFilterWhere(['like', 'org_study_group.study_group_name', $this->getAttribute('study_group_name')]);
        $query->andFilterWhere(['like', 'org_semester_type.semester_type', $this->getAttribute('semester_type')]);
        $query->andFilterWhere(['=', 'org_prog_level.programme_level', $this->getAttribute('programme_level')]);
        $query->andFilterWhere(['like', 'org_semester_code.semster_name', $this->getAttribute('semster_name')]);


        return $dataProvider;
    }


}
