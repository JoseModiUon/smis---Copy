<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrUnit;

/**
 * OrgProgCurrUnitSearch represents the model behind the search form of `app\models\OrgProgCurrUnit`.
 */
class OrgProgCurrUnitSearch extends OrgProgCurrUnit
{
    public $progCurriculum;
    public $orgUnitHistory;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_unit_id', 'org_unit_history_id', 'prog_curriculum_id'], 'integer'],
            [['start_date', 'end_date', 'status','orgUnitHistory','progCurriculum'], 'safe'],
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
//        $query = OrgProgCurrUnit::find();
        $query=OrgProgCurrUnit::find()->select([
            'org_unit.unit_name',
           'org_prog_curr_unit.prog_curriculum_unit_id' ,
            'org_prog_curr_unit.prog_curriculum_id',
            'org_prog_curr_unit.org_unit_history_id',
            'org_prog_curr_unit.start_date',
            'org_prog_curr_unit.end_date',
            'org_prog_curr_unit.status',
            'org_programme_curriculum.prog_curriculum_desc',

             ])->joinWith(['orgUnitHistory'=>function($q){
                  $q ->joinWith(['orgUnit']);
                  }])
                ->joinWith(['progCurriculum']);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'prog_curriculum_unit_id' => $this->prog_curriculum_unit_id,
            'org_unit_history_id' => $this->org_unit_history_id,
            'prog_curriculum_id' => $this->prog_curriculum_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $query->andFilterWhere(['ilike', 'status', $this->status])
        ->andFilterWhere(['ilike', 'org_programme_curriculum.prog_curriculum_desc', $this->progCurriculum])
        ->andFilterWhere(['ilike', 'org_unit.unit_name', $this->orgUnitHistory]);

        return $dataProvider;
    }
}
