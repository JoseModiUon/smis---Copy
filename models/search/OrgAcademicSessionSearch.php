<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgAcademicSession;

/**
 * OrgAcademicSessionSearch represents the model behind the search form of `app\models\OrgAcademicSession`.
 */
class OrgAcademicSessionSearch extends OrgAcademicSession
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_id'], 'integer'],
            [['acad_session_name', 'acad_session_desc', 'start_date', 'end_date'], 'safe'],
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
        $query = OrgAcademicSession::find();

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
            'acad_session_id' => $this->acad_session_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $query->andFilterWhere(['ilike', 'acad_session_name', $this->acad_session_name])
            ->andFilterWhere(['ilike', 'acad_session_desc', $this->acad_session_desc]);

        return $dataProvider;
    }
}
