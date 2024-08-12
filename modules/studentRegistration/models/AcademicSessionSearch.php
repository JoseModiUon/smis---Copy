<?php

namespace app\modules\studentRegistration\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\studentRegistration\models\AcademicSession;

/**
 * AcademicSessionSearch represents the model behind the search form of `app\modules\studentRegistration\models\AcademicSession`.
 */
class AcademicSessionSearch extends AcademicSession
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_id'], 'integer'],
            [['acad_session_name', 'acad_session_desc', 'start_date', 'end_date', 'userid', 'ip_address', 'user_action', 'last_update'], 'safe'],
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
        $query = AcademicSession::find();

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
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['ilike', 'acad_session_name', $this->acad_session_name])
            ->andFilterWhere(['ilike', 'acad_session_desc', $this->acad_session_desc])
            ->andFilterWhere(['ilike', 'userid', $this->userid])
            ->andFilterWhere(['ilike', 'ip_address', $this->ip_address])
            ->andFilterWhere(['ilike', 'user_action', $this->user_action]);

        return $dataProvider;
    }
}
