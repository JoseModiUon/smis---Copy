<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgCohortSession;
use yii\db\ActiveQuery;

/**
 * OrgCohortSessionSearch represents the model behind the search form of `app\models\OrgCohortSession`.
 */
class OrgCohortSessionSearch extends OrgCohortSession
{
    public $cohort;
    public $acadProgDesc;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cohort_session_id', 'cohort_id', 'prog_curriculum_semester_id'], 'integer'],
            [['cohort_session_name', 'status', 'cohort','acadProgDesc'], 'safe'],
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
        $query = OrgCohortSession::find();
        $query->joinWith([
            'cohort',
            'progCurriculumSemester.acadSessionSemester',
            'progCurriculumSemester.progCurriculum'
        ]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['cohort'] = [

            'asc' => ['org_cohort.cohort_desc' => SORT_ASC],
            'desc' => ['org_cohort.cohort_desc' => SORT_DESC],
        ];


        $dataProvider->sort->attributes['acadProgDesc'] = [

            'asc' => ['org_academic_session_semester.acad_session_semester_desc' => SORT_ASC],
            'desc' => ['org_academic_session_semester.acad_session_semester_desc' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'cohort_session_id' => $this->cohort_session_id,
            'cohort_id' => $this->cohort_id,
            'prog_curriculum_semester_id' => $this->prog_curriculum_semester_id,
        ]);

        $query->andFilterWhere(['ilike', 'cohort_session_name', $this->cohort_session_name])
            ->andFilterWhere(['ilike', 'org_cohort.cohort_desc', $this->cohort])
            ->andFilterWhere(['ilike', 'org_cohort_session.status', $this->status])
            ->orFilterWhere(['ilike', 'org_academic_session_semester.acad_session_semester_desc', $this->acadProgDesc])
            ->orFilterWhere(['ilike', 'org_programme_curriculum.prog_curriculum_desc', $this->acadProgDesc]);

        return $dataProvider;
    }
}
