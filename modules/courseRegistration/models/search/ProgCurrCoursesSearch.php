<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 6/14/2024
 * @time: 5:07 PM
 */

namespace app\modules\courseRegistration\models\search;

use app\modules\studentRegistration\models\ProgrammeCurriculumCourse;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

final class ProgCurrCoursesSearch extends ProgrammeCurriculumCourse
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'course_code',
            'course_name'
        ]);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'level_of_study',
                    'semester',
                    'course_code',
                    'course_name'
                ],
                'safe'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = (new Query())
            ->select([
                'pcc.prog_curriculum_course_id',
                'pcc.level_of_study',
                'pcc.semester',
                'cse.course_code',
                'cse.course_name'
            ])
            ->from('smis.org_prog_curr_course pcc')
            ->innerJoin('smis.org_courses cse', 'cse.course_id=pcc.course_id')
            ->where([
                'pcc.prog_curriculum_id' => $params['progCurrId'],
                'pcc.level_of_study' => $params['level']
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ]
        ]);

        $this->load($params['queryParams']);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['pcc.level_of_study' => $this->level_of_study]);
        $query->andFilterWhere(['pcc.semester' => $this->semester]);
        $query->andFilterWhere(['like', 'cse.course_code', $this->getAttribute('course_code')]);
        $query->andFilterWhere(['like', 'cse.course_name', $this->getAttribute('course_name')]);

        $query->orderBy([
            'pcc.level_of_study' => SORT_ASC,
            'pcc.semester' => SORT_ASC
        ]);

        return $dataProvider;
    }
}