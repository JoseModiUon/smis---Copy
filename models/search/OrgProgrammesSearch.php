<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgrammes;

/**
 * OrgProgrammesSearch represents the model behind the search form of `app\models\OrgProgrammes`.
 */
class OrgProgrammesSearch extends OrgProgrammes
{

    public $progType;
    public $progCat;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_id', 'prog_type_id', 'prog_cat_id'], 'integer'],
            [['prog_code', 'prog_short_name', 'prog_full_name', 'status','progType','progCat'], 'safe'],
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
        $query = OrgProgrammes::find();

        // add conditions that should always apply here
        $query->joinWith(['progCat','progType']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['progCat'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_prog_category.prog_cat_name' => SORT_ASC],
            'desc' => ['org_prog_category.prog_cat_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['progType'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['org_prog_type.prog_type_name' => SORT_ASC],
            'desc' => ['org_prog_type.prog_type_name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'prog_id' => $this->prog_id,
            'prog_type_id' => $this->prog_type_id,
            'prog_cat_id' => $this->prog_cat_id,
        ]);

        $query->andFilterWhere(['ilike', 'prog_code', $this->prog_code])
            ->andFilterWhere(['ilike', 'prog_short_name', $this->prog_short_name])
            ->andFilterWhere(['ilike', 'prog_full_name', $this->prog_full_name])
            ->andFilterWhere(['ilike', 'status', $this->status])
            ->andFilterWhere(['ilike', 'smis.org_prog_type.prog_type_name', $this->progType])
            ->andFilterWhere(['ilike', 'smis.org_prog_category.prog_cat_name', $this->progCat]);

        return $dataProvider;
    }
}
