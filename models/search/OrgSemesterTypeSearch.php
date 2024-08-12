<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgSemesterType;

/**
 * OrgSemesterTypeSearch represents the model behind the search form of `app\models\OrgSemesterType`.
 */
class OrgSemesterTypeSearch extends OrgSemesterType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semester_type_id'], 'integer'],
            [['semester_type'], 'safe'],
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
        $query = OrgSemesterType::find();

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
            'semester_type_id' => $this->semester_type_id,
        ]);

        $query->andFilterWhere(['ilike', 'semester_type', $this->sem_type]);

        return $dataProvider;
    }
}
