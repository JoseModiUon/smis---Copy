<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/27/2023
 * @time: 3:53 PM
 */

namespace app\modules\examManagement\models\search;

use app\modules\studentRegistration\models\StudentCourse;
use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class MarksSearch extends StudentCourse
{
    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'course_registration_id',
                    'grade',
                    'examtype_code',
                    'remarks',
                    'last_update'
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
     * @param array $moreParams
     * @return ActiveDataProvider
     * @throws \Exception
     */
    public function search(array $params, array $moreParams = []): ActiveDataProvider
    {
        $mrksheetId = $moreParams['mrksheetId'];

        $query = StudentCourse::find()->alias('sc')
            ->select([
                'sc.student_courses_id',
                'sc.course_registration_id',
                'sc.course_mark',
                'sc.exam_mark',
                'sc.final_mark',
                'sc.grade',
                'sc.examtype_code',
                'sc.remarks',
                'sc.last_update'
            ])
            ->where(['sc.mrksheet_id' => $mrksheetId])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ],
        ]);

        $this->load($params);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'sc.course_registration_id', $this->course_registration_id . '%', false]);
        $query->andFilterWhere(['like', 'sc.grade', $this->grade]);
        $query->andFilterWhere(['like', 'sc.examtype_code', $this->examtype_code]);
        $query->andFilterWhere(['like', 'sc.remarks', $this->remarks]);

        if(!empty($params['MarksSearch']['last_update'])){
            $lastUpdate = $params['MarksSearch']['last_update'];
            $lastUpdateStart = new DateTime(substr($lastUpdate, 0,10));
            $lastUpdateEnd = new DateTime(substr($lastUpdate, 13));
            $query->andFilterWhere(['between', 'sc.last_update', $lastUpdateStart->format('Y-m-d'),
                $lastUpdateEnd->format('Y-m-d')]);
        }

        return $dataProvider;
    }
}
