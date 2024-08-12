<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrSemester;
use yii\db\ActiveQuery;

/**
 * OrgProgCurrSemesterSearch represents the model behind the search form of `app\models\OrgProgCurrSemester`.
 */
class OrgProgCurrSemesterSearch extends OrgProgCurrSemester
{
    public $acadSessionSemester;
    public $progCurriculum;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_semester_id', 'prog_curriculum_id', 'acad_session_semester_id'], 'integer'],
            [['acadSessionSemester', 'progCurriculum'], 'safe']
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
        $query = OrgProgCurrSemester::find();

        $query->joinWith(['acadSessionSemester']);
        $query->joinWith(['progCurriculum' => function (ActiveQuery $q) {
            $q->joinWith('prog');
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ]
        ]);

        // add conditions that should always apply here
        $dataProvider->sort->attributes['acadSessionSemester'] = [

            'asc' => ['org_academic_session_semester.acad_session_semester_desc' => SORT_ASC],
            'desc' => ['org_academic_session_semester.acad_session_semester_desc' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['progCurriculum'] = [

            'asc' => ['org_programme_curriculum.prog_curriculum_desc' => SORT_ASC],
            'desc' => ['org_programme_curriculum.prog_curriculum_desc' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'prog_curriculum_semester_id' => $this->prog_curriculum_semester_id,
            'prog_curriculum_id' => $this->prog_curriculum_id,
            'acad_session_semester_id' => $this->acad_session_semester_id,
        ]);

        $query
            ->andFilterWhere(['ilike', 'org_programme_curriculum.prog_curriculum_desc', $this->progCurriculum])
            ->andFilterWhere(['ilike', 'org_academic_session_semester.acad_session_semester_desc', $this->acadSessionSemester]);

        return $dataProvider;
    }
}
