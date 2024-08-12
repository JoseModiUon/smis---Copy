<?php

namespace app\modules\feesManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\ProgrammeCurriculum;

/**
 * ProgrammeCurriculumSearch represents the model behind the search form of `app\modules\feesManagement\models\ProgrammeCurriculum`.
 */
class FeesCollectionSearch extends ProgrammeCurriculum
{
    public $prog_code;
    public $billing_type_desc;
    public $registration_number;
    public $academic_progress_id;
    public $acad_session_name;
    public $adm_refno;
    public $start_date;
    public $end_date;
    public $study_group_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_id', 'prog_id', 'duration', 'pass_mark', 'annual_semesters', 'max_units_per_semester', 'grading_system_id', 'billing_type_id'], 'integer'],
            [['start_date', 'end_date'], 'date', 'format' => 'php:Y-m-d'],
            ['start_date', 'validateDates'],
            [['prog_curriculum_desc', 'average_type', 'award_rounding', 'start_date', 'end_date', 'prog_cur_prefix', 'date_created', 'status', 'approval_date', 'userid', 'ip_address', 'user_action', 'last_update', 'billing_type_desc', 'prog_code', 'registration_number', 'academic_progress_id', 'acad_session_name', 'adm_refno', 'study_group_name'], 'safe'],
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
        $query = ProgrammeCurriculum::find()->joinWith(['billingType', 'orgProgrammes']);
        $query->joinWith(['studentProgrammeCurriculum']);
        $query->joinWith(['studentProgrammeCurriculum'=>function($n){
            $n->joinWith(['admittedStudent.studentGroup']);
            $n->joinWith(['academicProgress.academicSession']);
        }]);

        // studentProgrammeCurriculum.academicProgress.academicSession.acad_session_name

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);


        $dataProvider->sort->attributes['billing_type_desc'] = [
            'asc' => ['fss_billing_type.billing_type_desc' => SORT_ASC],
            'desc' => ['fss_billing_type.billing_type_desc' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['prog_code'] = [
            'asc' => ['org_programmes.prog_code' => SORT_ASC],
            'desc' => ['org_programmes.prog_code' => SORT_DESC],
        ];

        $this->load($params);
    
         // Set default values if not provided
         if (empty($this->prog_curriculum_id)) {
            $this->prog_curriculum_id = 316;
        }
        if (empty($this->acad_session_name)) {
            $this->acad_session_name = '2022/2023';
        }

        if (empty($this->acad_session_name)) {
            $this->acad_session_name = '2022/2023';
        }

        if (empty($this->end_date)) {
            $this->end_date = date('Y-m-d');
        }

        if (empty($this->start_date)) {
            $this->start_date = date('Y-m-d');
        }


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            // 'prog_curriculum_id' => $this->prog_curriculum_id,
            'prog_id' => $this->prog_id,
            'duration' => $this->duration,
            'pass_mark' => $this->pass_mark,
            'annual_semesters' => $this->annual_semesters,
            'max_units_per_semester' => $this->max_units_per_semester,
            // 'start_date' => $this->start_date,
            // 'end_date' => $this->end_date,
            'date_created' => $this->date_created,
            'grading_system_id' => $this->grading_system_id,
            'approval_date' => $this->approval_date,
            'last_update' => $this->last_update,
            'billing_type_id' => $this->billing_type_id,
        ]);

        $query->andFilterWhere(['ilike', 'prog_curriculum_desc', $this->prog_curriculum_desc])
            ->andFilterWhere(['ilike', 'average_type', $this->average_type])
            ->andFilterWhere(['ilike', 'award_rounding', $this->award_rounding])
            ->andFilterWhere(['ilike', 'prog_cur_prefix', $this->prog_cur_prefix])
            ->andFilterWhere(['ilike', 'status', $this->status])
            ->andFilterWhere(['ilike', 'userid', $this->userid])
            ->andFilterWhere(['ilike', 'ip_address', $this->ip_address])
            ->andFilterWhere(['ilike', 'user_action', $this->user_action])
            ->andFilterWhere(['ilike', 'fss_billing_type.billing_type_desc', $this->billing_type_desc])
            ->andFilterWhere(['ilike', 'org_programmes.prog_code', $this->prog_code])
            ->andFilterWhere(['like', 'sm_student_programme_curriculum.registration_number', $this->registration_number])
            ->andFilterWhere(['like', 'acad_session_name', $this->acad_session_name])
            ->andFilterWhere(['like', 'sm_student_group.adm_refno', $this->adm_refno])
            ;

        return $dataProvider;
    }



    public function validateDates($attribute, $params)
    {
        if (strtotime($this->start_date) > strtotime($this->end_date)) {
            $this->addError($attribute, 'Start date cannot be greater than end date.');
        }
    }
}

