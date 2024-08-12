<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgCoursePrerequisite;

/**
 * OrgCoursePrerequisiteSearch represents the model behind the search form of `app\models\OrgCoursePrerequisite`.
 */
class OrgCoursePrerequisiteSearch extends OrgCoursePrerequisite
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_prerequisite_id', 'prog_curriculum_course_id', 'course_id'], 'integer'],
            [['status','course','progCurCourse'], 'safe'],
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
        $query = (new \yii\db\Query())
        ->select([
            'course_prerequisite_id',
            'org_course_prerequisite.status',
            'org_course_prerequisite.prog_curriculum_course_id',
            'prog_curriculum_desc',
            'course_name',

        ])
        ->from('smis.org_prog_curr_course')
        ->innerJoin('smis.org_programme_curriculum','org_programme_curriculum.prog_curriculum_id = org_prog_curr_course.prog_curriculum_id')
        ->innerJoin('smis.org_courses','org_courses.course_id = org_prog_curr_course.course_id')
        ->innerJoin('smis.org_course_prerequisite','org_course_prerequisite.course_id = org_course_prerequisite.course_id');
        
        // $query = OrgCoursePrerequisite::find()
        // ->select([
        //     'course_prerequisite_id',
        //     'org_course_prerequisite.status',
        //     'org_course_prerequisite.prog_curriculum_course_id',
        //     'prog_curriculum_desc',
        //     'course_name',

        // ])
        // ->innerJoin('smis.org_prog_curr_course','org_prog_curr_course.prog_curriculum_course_id = org_course_prerequisite.prog_curriculum_course_id')
        // ->innerJoin('smis.org_programme_curriculum','org_programme_curriculum.prog_curriculum_id = org_prog_curr_course.prog_curriculum_id')
        // ->innerJoin('smis.org_courses','org_courses.course_id = org_prog_curr_course.course_id');
        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['course'] = [

            'asc' => ['org_courses.course_name' => SORT_ASC],
            'desc' => ['org_courses.course_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['progCurCourse'] = [

            'asc' => ['org_programme_curriculum.prog_curriculum_desc' => SORT_ASC],
            'desc' => ['org_programme_curriculum.prog_curriculum_desc' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['ilike', 'status', $this->status])
            ->andFilterWhere(['ilike', 'org_courses.course_name', $this->course])
            ->orFilterWhere(['ilike', 'org_courses.course_name', $this->progCurCourse])
            ->orFilterWhere(['ilike', 'org_programme_curriculum.prog_curriculum_desc', $this->progCurCourse]);

        return $dataProvider;
    }
}
