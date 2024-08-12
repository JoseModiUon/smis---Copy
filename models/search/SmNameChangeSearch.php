<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmNameChange;
use yii\db\ActiveQuery;

/**
 * SmNameChangeSearch represents the model behind the search form of `app\models\SmNameChange`.
 */
class SmNameChangeSearch extends SmNameChange
{
    public $student;
    public $orgUnit;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_change_id', 'student_id'], 'integer'],
            [['request_date', 'new_surname', 'new_othernames', 'reason', 'document_url', 'current_surname', 'current_othernames', 'status','student','orgUnit'], 'safe'],
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
    public function search($params, $filters)
    {
        $query = (new \yii\db\Query())
        ->select([
            'sm_name_change.name_change_id',
            'sm_name_change.request_date',
            'sm_student.student_number',
            'sm_name_change.student_id',
            'sm_name_change.new_surname',
            'sm_name_change.new_othernames',
            'sm_name_change.reason',
            'sm_name_change.document_url',
            'sm_name_change.current_surname',
            'sm_name_change.current_othernames',
            'sm_name_change.status',
            'org_unit_history.org_unit_history_id',
            'org_unit_history.parent_id',
            'org_unit.unit_name',
            'org_unit.unit_id'
        ])
        ->from('smis.sm_name_change')
        ->distinct()
        ->leftJoin('smis.sm_student', 'sm_student.student_id = sm_name_change.student_id')
        ->leftJoin('smis.sm_student_programme_curriculum', 'sm_student_programme_curriculum.student_id = sm_student.student_id')
        ->leftJoin('smis.org_programme_curriculum', 'org_programme_curriculum.prog_curriculum_id=sm_student_programme_curriculum.prog_curriculum_id')
        ->leftJoin('smis.org_prog_curr_unit', 'org_prog_curr_unit.prog_curriculum_id=org_programme_curriculum.prog_curriculum_id')
        ->leftJoin('smis.org_unit_history', 'org_unit_history.org_unit_history_id=org_prog_curr_unit.org_unit_history_id')
        ->leftJoin('smis.org_unit', 'org_unit.unit_id=org_unit_history.org_unit_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['student'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['student.student_number' => SORT_ASC],
            'desc' => ['student.student_number' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        // $query->andFilterWhere([
        //     'name_change_id' => $this->name_change_id,
        //     'request_date' => $this->request_date,
        //     'student_id' => $this->student_id,
        // ]);
        $query->andFilterWhere(['ilike', 'new_surname', $this->new_surname])
            ->andFilterWhere(['ilike', 'new_othernames', $this->new_othernames])
            ->andFilterWhere(['ilike', 'reason', $this->reason])
            ->andFilterWhere(['ilike', 'document_url', $this->document_url])
            ->andFilterWhere(['ilike', 'current_surname', $this->current_surname])
            ->andFilterWhere(['ilike', 'current_othernames', $this->current_othernames])
            ->andFilterWhere(['ilike', 'smis.sm_student.student_number', $this->student])
            ->andFilterWhere(['ilike', 'org_unit.unit_name', $this->orgUnit])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        //removes records with approved and rejected status
        if ($filters['filterCompleted']) {
            $query
                ->andFilterWhere(['!=', 'sm_name_change.status', 'APPROVED'])
                ->andFilterWhere(['!=', 'sm_name_change.status', 'REJECTED']);
        }

        return $dataProvider;
    }
}
