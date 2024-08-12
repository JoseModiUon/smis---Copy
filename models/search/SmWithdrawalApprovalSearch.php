<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmWithdrawalApproval;

/**
 * SmWithdrawalApprovalSearch represents the model behind the search form of `app\models\SmWithdrawalApproval`.
 */
class SmWithdrawalApprovalSearch extends SmWithdrawalApproval
{
    public $level;
    public $approver;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['withdrawal_approval_id', 'withdrawal_request_id', 'approver_id'], 'integer'],
            [['comments', 'approval_status','level','approver'], 'safe'],
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
        $query = SmWithdrawalApproval::find();

        $query->select([
            'sm_withdrawal_approval.withdrawal_approval_id',
            'sm_withdrawal_approval.withdrawal_request_id',
            'sm_withdrawal_approval.approver_id',
            'sm_withdrawal_approval.approval_status',
            'sm_withdrawal_approval.comments',
            'sm_approver.approver',
            'sm_approver.level',
            'sm_withdrawal_request.reason',
            'sm_withdrawal_request.request_date'
        ])
        ->joinWith(['approvals','withdrawalRequest']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['level'] = [
            'asc' => ['sm_approver.level' => SORT_ASC],
            'desc' => ['sm_approver.level' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['approver'] = [
            'asc' => ['sm_approver.approver' => SORT_ASC],
            'desc' => ['sm_approver.approver' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'withdrawal_approval_id' => $this->withdrawal_approval_id,
            'sm_withdrawal_approval.withdrawal_request_id' => $params['withdrawal_request_id'],
            'approver_id' => $this->approver_id,
        ]);

        $query->andFilterWhere(['ilike', 'comments', $this->comments])
            ->andFilterWhere(['ilike', 'sm_withdrawal_approval.approval_status', $this->approval_status])
            ->andFilterWhere(['ilike', 'sm_approver.level', $this->level])
            ->andFilterWhere(['ilike', 'sm_approver.approver', $this->approver]);

        return $dataProvider;
    }
}
