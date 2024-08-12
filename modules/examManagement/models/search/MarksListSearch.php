<?php
/** author Jeff Wahome <wahome4jeff@gmail.com> */

namespace app\modules\examManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\courseRegistration\models\CrProgCurrTimetable;
use yii\db\Query;

/**
 * FullClassListSearch represents the model behind the search form of `app\modules\courseRegistration\models\CrProgCurrTimetable`.
 */
class MarksListSearch extends CrProgCurrTimetable
{
    public function attributes():array
    {
        return array_merge(parent::attributes(), [
            'course_name',
            'course_code',
            'acad_session_name',
            'study_centre_name',
            'study_group_name',
            'semester_type',
            'semster_name',
            'academic_level_name'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return 
        [
            [['course_name','course_code','acad_session_name','study_centre_name','study_group_name','semester_type','semster_name',
            'academic_level_name'], 'safe']
        ];
    }
    

    /**
     * {@inheritdoc}
     */
    public function scenarios():array
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
    public function search($params):ActiveDataProvider
    {
        $query = (new Query())
            ->select([
                'cpct.timetable_id',
                'oc.course_id',
                'oc.course_code',
                'oc.course_name',
                'oas.acad_session_name',
                'osc.study_centre_name',
                'osg.study_group_name',
                'ost.semester_type',
                'osc1.semster_name',
                'opcsg.programme_level',
                'st.student_id',
                'oal.academic_level_name'
            ])
            ->from('smis.ex_marksheet em')
            ->innerJoin('smis.cr_course_registration ccr', 'ccr.student_course_reg_id=em.student_course_reg_id')
            ->innerJoin('smis.cr_prog_curr_timetable cpct', 'cpct.timetable_id=ccr.timetable_id')
            ->innerJoin('smis.org_prog_curr_course opcc', 'opcc.prog_curriculum_course_id=cpct.prog_curriculum_course_id')
            ->innerJoin('smis.org_prog_curr_semester_group opcsg', 'opcsg.prog_curriculum_sem_group_id=cpct.prog_curriculum_sem_group_id')
            ->innerJoin('smis.org_study_centre_group oscg', 'oscg.study_centre_group_id=opcsg.study_centre_group_id')
            ->innerJoin('smis.org_study_centre osc', 'osc.study_centre_id=oscg.study_centre_id')
            ->innerJoin('smis.org_study_group osg', 'osg.study_group_id=oscg.study_group_id')
            ->innerJoin('smis.org_prog_curr_semester opcs', 'opcs.prog_curriculum_semester_id=opcsg.prog_curriculum_semester_id')
            ->innerJoin('smis.org_semester_type ost', 'ost.semester_type_id=opcs.semester_type_id')
            ->innerJoin('smis.org_academic_session_semester oass', 'oass.acad_session_semester_id=opcs.acad_session_semester_id')
            ->innerJoin('smis.org_semester_code osc1','osc1.semester_code=oass.semester_code')
            ->innerJoin('smis.org_academic_session oas', 'oas.acad_session_id=oass.acad_session_id')
            ->innerJoin('smis.org_courses oc', 'oc.course_id=opcc.course_id')
            ->innerJoin('smis.sm_student_sem_session_progress ssssp', 'ssssp.student_semester_session_id=ccr.student_semester_session_id')
            ->innerJoin('smis.sm_academic_progress sap', 'sap.academic_progress_id=ssssp.academic_progress_id')
            ->innerJoin('smis.org_academic_levels oal', 'oal.academic_level_id=sap.academic_level_id')
            ->innerJoin('smis.sm_student_programme_curriculum sspc', 'sspc.student_prog_curriculum_id=sap.student_prog_curriculum_id')
            ->innerJoin('smis.sm_student st', 'st.student_id=sspc.student_id');

        $this->load($params);

        $query->andFilterWhere(['ilike', 'course_name', $this->getAttribute('course_name')]);
        $query->andFilterWhere(['ilike', 'oc.course_code', $this->getAttribute('course_code')]);
        $query->andFilterWhere(['like', 'oas.acad_session_name', $this->getAttribute('acad_session_name')]);
        $query->andFilterWhere(['like', 'osc.study_centre_name', $this->getAttribute('study_centre_name')]);
        $query->andFilterWhere(['like', 'osg.study_group_name', $this->getAttribute('study_group_name')]);
        $query->andFilterWhere(['like', 'ost.semester_type', $this->getAttribute('semester_type')]);
        $query->andFilterWhere(['ilike', 'oal.academic_level_name', $this->getAttribute('academic_level_name')]);
        $query->andFilterWhere(['like', 'osc1.semster_name', $this->getAttribute('semster_name')]);


        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 50,
            ],
        ]);
    }
}
