<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgStudyCentreGroup;

/**
 * OrgStudyCentreGroupSearch represents the model behind the search form of `app\models\OrgStudyCentreGroup`.
 */
class OrgStudyCentreGroupSearch extends OrgStudyCentreGroup
{
    public $studyGroup;
    public $studyCentre;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['study_centre_group_id', 'study_centre_id', 'study_group_id'], 'integer'],
            [['status','studyGroup','studyCentre'], 'safe',],
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
        $query = OrgStudyCentreGroup::find();

        // add conditions that should always apply here
        $query->joinWith(['studyCentre','studyGroup']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['studyCentre'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_study_centre.study_centre_name' => SORT_ASC],
            'desc' => ['org_study_centre.study_centre_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['studyGroup'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_study_group.study_group_name' => SORT_ASC],
            'desc' => ['org_study_group.study_group_name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'study_centre_group_id' => $this->study_centre_group_id,
            'study_centre_id' => $this->study_centre_id,
            'study_group_id' => $this->study_group_id,
//            'study_group_id' => $this->study_group_id,
//            'study_centre_id' => $this->study_centre_id,
        ]);

        $query->andFilterWhere(['ilike', 'status', $this->status])
            ->andFilterWhere(['ilike', 'smis.org_study_centre.study_centre_name', $this->studyCentre])
            ->andFilterWhere(['ilike', 'smis.org_study_group.study_group_name', $this->studyGroup]);

        return $dataProvider;
    }
}
