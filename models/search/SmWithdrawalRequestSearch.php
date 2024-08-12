<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmWithdrawalRequest;

/**
 * SmWithdrawalRequestSearch represents the model behind the search form of `app\models\SmWithdrawalRequest`.
 */
class SmWithdrawalRequestSearch extends SmWithdrawalRequest
{
	public $smWithdrawalType;
	public $student_othernames;
	public $student_surname;
	public $student_number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['withdrawal_request_id', 'withdrawal_type_id', 'student_id'], 'integer'],
            [['request_date', 'reason', 'approval_status','smWithdrawalType','student_number','student_surname','student_othernames'], 'safe'],
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
    public function search($params, $filters=[])
    {
        $query = SmWithdrawalRequest::find();

        // add conditions that should always apply here
		$query->joinWith(['smWithdrawalType','student']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		 $dataProvider->sort->attributes['smWithdrawalType'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['sm_withdrawal_type.withdrawal_type_name' => SORT_ASC],
            'desc' => ['sm_withdrawal_type.withdrawal_type_name' => SORT_DESC],
        ];
		$dataProvider->sort->attributes['student_number'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['sm_student.student_number' => SORT_ASC],
            'desc' => ['sm_student.student_number' => SORT_DESC],
        ];
		$dataProvider->sort->attributes['student_othernames'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['sm_student.other_names' => SORT_ASC],
            'desc' => ['sm_student.other_names' => SORT_DESC],
        ];
		$dataProvider->sort->attributes['student_surname'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['sm_student.surname' => SORT_ASC],
            'desc' => ['sm_student.surname' => SORT_DESC],
        ];
		

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'withdrawal_request_id' => $this->withdrawal_request_id,
            'withdrawal_type_id' => $this->withdrawal_type_id,
            'request_date' => $this->request_date,
            'student_id' => $this->student_id,
        ]);

        $query->andFilterWhere(['ilike', 'reason', $this->reason])
		    ->andFilterWhere(['ilike', 'sm_withdrawal_type.withdrawal_type_name', $this->smWithdrawalType])
		    ->andFilterWhere(['ilike', 'sm_student.surname', $this->student_surname])
		    ->andFilterWhere(['ilike', 'sm_student.other_names', $this->student_othernames])
		    ->andFilterWhere(['ilike', 'sm_student.student_number', $this->student_number])
            ->andFilterWhere(['ilike', 'approval_status', $this->approval_status]);

                //removes records with approved and rejected status
        if ($filters['filterCompleted']) {
            $query
                ->andFilterWhere(['!=', 'sm_withdrawal_request.approval_status', 'APPROVED'])
                ->andFilterWhere(['!=', 'sm_withdrawal_request.approval_status', 'REJECTED']);
        }
        return $dataProvider;
    }
}
