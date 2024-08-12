<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgUnitHistory;

/**
 * OrgUnitHistorySearch represents the model behind the search form of `app\models\OrgUnitHistory`.
 */
class OrgUnitHistorySearch extends OrgUnitHistory
{
    public $orgType;
    public $successorUnit;
    public $parentUnit;
    public $unitHead;
    public $orgUnit;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_unit_history_id', 'org_unit_id', 'org_type_id', 'parent_id', 'successor_id', 'unit_head_id', 'unit_head_user_id', 'user_id'], 'integer'],
            [['start_date', 'end_date', 'date_created','orgUnit','orgType','successorUnit','parentUnit','unitHead'], 'safe'],
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
        $query = OrgUnitHistory::find();


        // add conditions that should always apply here
        $query->joinWith(['orgUnit','parentUnit','orgType','unitHead','successorUnit']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['successorUnit'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_unit.unit_name' => SORT_ASC],
            'desc' => ['org_unit.unit_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['unitHead'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_unit_head.unit_head_name' => SORT_ASC],
            'desc' => ['org_unit_head.unit_head_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['orgUnit'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_unit.unit_name' => SORT_ASC],
            'desc' => ['org_unit.unit_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['orgType'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_unit_types.unit_type_name' => SORT_ASC],
            'desc' => ['org_unit_types.unit_type_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['parentUnit'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_unit.unit_name' => SORT_ASC],
            'desc' => ['org_unit.unit_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'org_unit_history_id' => $this->org_unit_history_id,
            'org_unit_id' => $this->org_unit_id,
            'org_type_id' => $this->org_type_id,
            'parent_id' => $this->parent_id,
            'successor_id' => $this->successor_id,
            'unit_head_id' => $this->unit_head_id,
            'unit_head_user_id' => $this->unit_head_user_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user_id' => $this->user_id,
            'date_created' => $this->date_created,
        ]);
        $query->andFilterWhere(['ilike', 'smis.org_unit.unit_name', $this->orgUnit])
            ->andFilterWhere(['ilike', 'smis.org_unit.unit_name', $this->successorUnit])
            ->andFilterWhere(['ilike', 'smis.org_unit.unit_name', $this->parentUnit])
            ->andFilterWhere(['ilike', 'smis.org_unit_head.unit_head_name', $this->unitHead])
            ->andFilterWhere(['ilike', 'smis.org_unit_types.unit_type_name', $this->orgType]);

        return $dataProvider;
    }
}
