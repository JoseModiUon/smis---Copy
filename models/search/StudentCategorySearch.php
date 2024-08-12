<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmStudentCategory;

/**
 * StudentCategorySearch represents the model behind the search form of `app\models\SmStudentCategory`.
 */
class StudentCategorySearch extends SmStudentCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['std_category_id'], 'integer'],
            [['std_category_name'], 'safe'],
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
        $query = SmStudentCategory::find();

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
            'std_category_id' => $this->std_category_id,
        ]);

        $query->andFilterWhere(['ilike', 'std_category_name', $this->std_category_name]);

        return $dataProvider;
    }
}
