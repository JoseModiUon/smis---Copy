<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgStudyGroup;

/**
 * OrgStudyGroupSearch represents the model behind the search form of `app\models\OrgStudyGroup`.
 */
class OrgStudyGroupSearch extends OrgStudyGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['study_group_id'], 'integer'],
            [['study_group_name', 'status'], 'safe'],
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
        $query = OrgStudyGroup::find();

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
            'study_group_id' => $this->study_group_id,
        ]);

        $query->andFilterWhere(['ilike', 'study_group_name', $this->study_group_name])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
