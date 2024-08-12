<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExGradingSystemDetail;

/**
 * ExGradingSystemDetailSearch represents the model behind the search form of `app\models\ExGradingSystemDetail`.
 */
class ExGradingSystemDetailSearch extends ExGradingSystemDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grading_system_detail_id', 'grading_system_id'], 'integer'],
            [['lower_bound', 'upper_bound', 'grade_point'], 'number'],
            [['grade', 'is_active', 'legend', 'extlegend', 'recomm_id', 'userid', 'ip_address', 'user_action', 'last_update'], 'safe'],
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
        $query = ExGradingSystemDetail::find()

            ->innerJoinWith(['gradingSystem'])
            ->where(['ex_grading_system_detail.is_active' => 'ACTIVE']);
        if ($params['grading_system_id']) {
            $query->andWhere(['ex_grading_system.grading_system_id' => $params['grading_system_id'] ?? '']);
        }



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
            'grading_system_detail_id' => $this->grading_system_detail_id,
            'ex_grading_system.grading_system_id' => $this->grading_system_id,
            'lower_bound' => $this->lower_bound,
            'upper_bound' => $this->upper_bound,
            'grade_point' => $this->grade_point,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['ilike', 'grade', $this->grade])
            ->andFilterWhere(['ilike', 'is_active', $this->is_active])
            ->andFilterWhere(['ilike', 'legend', $this->legend])
            ->andFilterWhere(['ilike', 'extlegend', $this->extlegend])
            ->andFilterWhere(['ilike', 'recomm_id', $this->recomm_id])
            ->andFilterWhere(['ilike', 'userid', $this->userid])
            ->andFilterWhere(['ilike', 'ip_address', $this->ip_address])
            ->andFilterWhere(['ilike', 'user_action', $this->user_action]);

        return $dataProvider;
    }
}
