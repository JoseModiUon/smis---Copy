<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExMarksheet;

/**
 * ExMarksheetSearch represents the model behind the search form of `app\models\ExMarksheet`.
 */
class ExMarksheetSearch extends ExMarksheet
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marksheet_id', 'student_course_reg_id', 'final_mark'], 'integer'],
            [['course_work_mark', 'exam_mark'], 'number'],
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
        $query = ExMarksheet::find();

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
            'marksheet_id' => $this->marksheet_id,
            'student_course_reg_id' => $this->student_course_reg_id,
            'course_work_mark' => $this->course_work_mark,
            'exam_mark' => $this->exam_mark,
            'final_mark' => $this->final_mark,
        ]);

        return $dataProvider;
    }
}
