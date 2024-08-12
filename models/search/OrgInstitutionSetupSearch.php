<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgInstitutionSetup;

/**
 * OrgInstitutionSetupSearch represents the model behind the search form of `app\models\OrgInstitutionSetup`.
 */
class OrgInstitutionSetupSearch extends OrgInstitutionSetup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['institution_setup_id', 'unit_id'], 'integer'],
            [['logo_url', 'favicon_url', 'motto', 'email', 'phone', 'website', 'postal_address'], 'safe'],
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
        $query = OrgInstitutionSetup::find();

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
            'institution_setup_id' => $this->institution_setup_id,
            'unit_id' => $this->unit_id,
        ]);

        $query->andFilterWhere(['ilike', 'logo_url', $this->logo_url])
            ->andFilterWhere(['ilike', 'favicon_url', $this->favicon_url])
            ->andFilterWhere(['ilike', 'motto', $this->motto])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'website', $this->website])
            ->andFilterWhere(['ilike', 'postal_address', $this->postal_address]);

        return $dataProvider;
    }
}
