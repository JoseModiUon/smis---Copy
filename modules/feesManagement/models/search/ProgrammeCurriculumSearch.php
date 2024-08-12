<?php

namespace app\modules\feesManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\ProgrammeCurriculum;

/**
 * ProgrammeCurriculumSearch represents the model behind the search form of `app\modules\feesManagement\models\ProgrammeCurriculum`.
 */
class ProgrammeCurriculumSearch extends ProgrammeCurriculum
{
    public $prog_code;
    public $billing_type_desc;
    public $registration_number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_id', 'prog_id', 'duration', 'pass_mark', 'annual_semesters', 'max_units_per_semester', 'grading_system_id', 'billing_type_id'], 'integer'],
            [['prog_curriculum_desc', 'average_type', 'award_rounding', 'start_date', 'end_date', 'prog_cur_prefix', 'date_created', 'status', 'approval_date', 'userid', 'ip_address', 'user_action', 'last_update', 'billing_type_desc', 'prog_code', 'registration_number'], 'safe'],
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
    // $query = ProgrammeCurriculum::find()
    // ->joinWith(['billingType', 'orgProgrammes'])
    // ->where(['org_programmes.prog_code' => ['CS203', 'CS502']]);

    $query = ProgrammeCurriculum::find()->joinWith(['billingType', 'orgProgrammes']);

        // dfvdf

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ]
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'prog_curriculum_id' => $this->prog_curriculum_id,
            'prog_id' => $this->prog_id,
            'duration' => $this->duration,
            'pass_mark' => $this->pass_mark,
            'annual_semesters' => $this->annual_semesters,
            'max_units_per_semester' => $this->max_units_per_semester,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
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
            ;

        return $dataProvider;
    }
}
