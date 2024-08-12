<?php

namespace app\modules\courseRegistration\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\courseRegistration\models\CrProgCurrTimetable;
use yii\db\Query;

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
            'programme_level'
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
            'programme_level'], 'safe']
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
                'opcsg.programme_level'
            ])
            ->from('smis.cr_prog_curr_timetable cpct')
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
            ->innerJoin('smis.org_courses oc', 'oc.course_id=opcc.course_id');

        $this->load($params);

        $query->andFilterWhere(['ilike', 'course_name', $this->getAttribute('course_name')]);
        $query->andFilterWhere(['ilike', 'oc.course_code', $this->getAttribute('course_code')]);
        $query->andFilterWhere(['like', 'oas.acad_session_name', $this->getAttribute('acad_session_name')]);
        $query->andFilterWhere(['like', 'osc.study_centre_name', $this->getAttribute('study_centre_name')]);
        $query->andFilterWhere(['like', 'osg.study_group_name', $this->getAttribute('study_group_name')]);
        $query->andFilterWhere(['like', 'ost.semester_type', $this->getAttribute('semester_type')]);
        $query->andFilterWhere(['=', 'opcsg.programme_level', $this->getAttribute('programme_level')]);
        $query->andFilterWhere(['like', 'osc1.semster_name', $this->getAttribute('semster_name')]);

        // if(!empty($params)) {
        //     $query->andFilterWhere([
        //         'cpct.timetable_id' => $params['timetable_id'],
        //         'oc.course_id' => $params['course_id'],
        //     ]);
        // }
        


        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
