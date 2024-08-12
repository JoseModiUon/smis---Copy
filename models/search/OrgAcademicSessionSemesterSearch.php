<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgAcademicSessionSemester;

/**
 * OrgAcademicSessionSemesterSearch represents the model behind the search form of `app\models\OrgAcademicSessionSemester`.
 */
class OrgAcademicSessionSemesterSearch extends OrgAcademicSessionSemester
{
    public $acadSession;
    public $semesterCode;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_semester_id', 'acad_session_id', 'semester_code'], 'integer'],
            [['acad_session_semester_desc','acadSession','semesterCode'], 'safe']
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
        $query = OrgAcademicSessionSemester::find();

        $query->joinWith(['acadSession','semesterCode']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['acadSession'] = [

            'asc' => ['org_academic_session.acad_session_name' => SORT_ASC],
            'desc' => ['org_academic_session.acad_session_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['semesterCode'] = [

            'asc' => ['org_semester_code.semster_name' => SORT_ASC],
            'desc' => ['org_semester_code.semster_name' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'acad_session_semester_id' => $this->acad_session_semester_id,
            'acad_session_id' => $this->acad_session_id,
            'semester_code' => $this->semester_code,
        ]);

        $query
            ->andFilterWhere(['ilike', 'smis.org_academic_session.acad_session_name', $this->acadSession])
            ->andFilterWhere(['ilike', 'acad_session_semester_desc', $this->acad_session_semester_desc]);

        return $dataProvider;
    }
}
