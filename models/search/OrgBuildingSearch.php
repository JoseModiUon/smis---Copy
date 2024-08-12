<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgBuilding;

/**
 * OrgBuildingSearch represents the model behind the search form of `app\models\OrgBuilding`.
 */
class OrgBuildingSearch extends OrgBuilding
{
    public $studycentre;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['building_id', 'floors', 'study_center'], 'integer'],
            [['building_code', 'building_name', 'studycentre'], 'safe'],
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
        $query = OrgBuilding::find();
        $query->joinWith(['studycenter']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['studycentre'] = [
            'asc' => ['org_study_centre.study_centre_name' => SORT_ASC],
            'desc' => ['org_study_centre.study_centre_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'building_id' => $this->building_id,
            'floors' => $this->floors,
            'study_center' => $this->study_center,
        ]);

        // dd($this->studycentre);

        $query
            ->andFilterWhere(['ilike', 'building_code', $this->building_code])
            ->orFilterWhere(['ilike', 'org_study_centre.study_centre_name', $this->studycentre])
            ->andFilterWhere(['ilike', 'building_name', $this->building_name]);

        return $dataProvider;
    }
}
