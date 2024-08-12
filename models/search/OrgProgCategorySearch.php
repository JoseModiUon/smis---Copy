<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCategory;

/**
 * OrgProgCategorySearch represents the model behind the search form of `app\models\OrgProgCategory`.
 */
class OrgProgCategorySearch extends OrgProgCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_cat_id', 'prog_cat_order'], 'integer'],
            [['prog_cat_code', 'prog_cat_name', 'status'], 'safe'],
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
        $query = OrgProgCategory::find();

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
            'prog_cat_id' => $this->prog_cat_id,
            'prog_cat_order' => $this->prog_cat_order,
        ]);

        $query->andFilterWhere(['ilike', 'prog_cat_code', $this->prog_cat_code])
            ->andFilterWhere(['ilike', 'prog_cat_name', $this->prog_cat_name])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
