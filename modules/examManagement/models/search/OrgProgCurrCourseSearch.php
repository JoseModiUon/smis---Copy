<?php

namespace app\modules\examManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\examManagement\models\OrgProgCurrCourse;

/**
 * OrgProgCurrCourseSearch represents the model behind the search form of `app\modules\examManagement\models\OrgProgCurrCourse`.
 */
class OrgProgCurrCourseSearch extends OrgProgCurrCourse
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_course_id', 'prog_curriculum_id', 'course_id', 'credit_factor', 'level_of_study', 'semester'], 'integer'],
            [['credit_hours'], 'number'],
            [['status'], 'safe'],
            [['has_course_work'], 'boolean'],
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
        $query = OrgProgCurrCourse::find();

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
            'prog_curriculum_course_id' => $this->prog_curriculum_course_id,
            'prog_curriculum_id' => $this->prog_curriculum_id,
            'course_id' => $this->course_id,
            'credit_factor' => $this->credit_factor,
            'credit_hours' => $this->credit_hours,
            'level_of_study' => $this->level_of_study,
            'semester' => $this->semester,
            'has_course_work' => $this->has_course_work,
        ]);

        $query->andFilterWhere(['ilike', 'status', $this->status]);
        $query->andFilterWhere(['prog_curriculum_id' => $params['prog_curriculum_id']]);
        $query->andFilterWhere(['level_of_study' => $params['prog_study_level']]);

        return $dataProvider;
    }
}
