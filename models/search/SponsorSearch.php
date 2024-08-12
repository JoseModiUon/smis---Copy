<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgSponsor;
use yii\db\Query;

/**
 * SponsorSearch represents the model behind the search form of `app\models\OrgSponsor`.
 */
class SponsorSearch extends OrgSponsor
{
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            [
            'country_name'
        ]
        );
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sponsor_name', 'country_code', 'country_name'], 'safe'],
            [['status'], 'boolean'],
            [['sponsor_id'], 'integer'],
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
        $query = (new Query())
        ->select([
            'os.sponsor_id',
            'os.country_code',
            'os.sponsor_name',
            'os.status',
            'oc.country_name'
        ])
        ->from('smis.org_sponsor os')
        ->innerJoin('smis.org_country oc', 'os.country_code=oc.country_code');

        $this->load($params);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
            'sponsor_id' => $this->sponsor_id,
            'country_code' => $this->country_code
        ]);

        $query->andFilterWhere(['ilike', 'sponsor_name', $this->sponsor_name])
            ->andFilterWhere(['ilike', 'country_code', $this->country_code])
            ->andFilterWhere(['ilike', 'oc.country_name', $this->country_name]);

        return $dataProvider;
    }
}
