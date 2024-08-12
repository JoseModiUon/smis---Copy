<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgrammeCurriculum;

/**
 * OrgProgrammeCurriculumSearch represents the model behind the search form of `app\models\OrgProgrammeCurriculum`.
 */
class OrgProgrammeCurriculumSearch extends OrgProgrammeCurriculum
{
    public $prog; 
    public $prog_code; 
    public $gradingSystem;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_id', 'prog_id', 'duration', 'pass_mark', 'annual_semesters', 'max_units_per_semester', 'grading_system_id'], 'integer'],
            [['prog_curriculum_desc', 'average_type', 'award_rounding', 'start_date', 'end_date', 'prog_cur_prefix', 'date_created', 'status', 'approval_date','prog', 'gradingSystem','prog_code'], 'safe'],
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
        $query = OrgProgrammeCurriculum::find();

        $query->joinWith(['prog', 'gradingSystem']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
 $dataProvider->sort->attributes['prog_code'] = [

            'asc' => ['org_programmes.prog_code' => SORT_ASC],
            'desc' => ['org_programmes.prog_code' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['prog'] = [

            'asc' => ['org_programmes.prog_short_name' => SORT_ASC],
            'desc' => ['org_programmes.prog_short_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['gradingSystem'] = [

            'asc' => ['ex_grading_system.grading_system_name' => SORT_ASC],
            'desc' => ['ex_grading_system.grading_system_name' => SORT_DESC],
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
        ]);

        $query->andFilterWhere(['ilike', 'prog_curriculum_desc', $this->prog_curriculum_desc])
            ->andFilterWhere(['ilike', 'average_type', $this->average_type])
            ->andFilterWhere(['ilike', 'award_rounding', $this->award_rounding])
            ->andFilterWhere(['ilike', 'org_programmes.prog_short_name', $this->prog])
            ->andFilterWhere(['ilike', 'org_programmes.prog_code', $this->prog_code])
            ->andFilterWhere(['ilike', 'ex_grading_system.grading_system_name', $this->gradingSystem])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
