<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgAcadSessionStatus;

/**
 * OrgAcadSessionStatusSearch represents the model behind the search form of `app\models\OrgAcadSessionStatus`.
 */
class OrgAcadSessionStatusSearch extends OrgAcadSessionStatus
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_status_id'], 'integer'],
            [['acad_session_status_name', 'session_status'], 'safe'],
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
        $query = OrgAcadSessionStatus::find();

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
            'acad_session_status_id' => $this->acad_session_status_id,
        ]);

        $query->andFilterWhere(['ilike', 'acad_session_status_name', $this->acad_session_status_name])
            ->andFilterWhere(['ilike', 'session_status', $this->session_status]);

        return $dataProvider;
    }
}
