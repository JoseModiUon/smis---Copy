<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgStudyCentre;

/**
 * OrgStudyCentreCodeSearch represents the model behind the search form of `app\models\OrgStudyCentre`.
 */
class OrgStudyCentreCodeSearch extends OrgStudyCentre
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['study_centre_id'], 'integer'],
            [['study_centre_name', 'status'], 'safe'],
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
        $query = OrgStudyCentre::find();

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
            'study_centre_id' => $this->study_centre_id,
        ]);

        $query->andFilterWhere(['ilike', 'study_centre_name', $this->study_centre_name])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
