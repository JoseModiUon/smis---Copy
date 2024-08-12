<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models\search;

use app\modules\studentRegistration\models\StudentProgCurriculum;
use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class RegisteredStudentsSearch extends StudentProgCurriculum
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'student.surname',
            'student.other_names',
            'student.registration_date',
            'progCurriculum.programme.prog_code',
            'progCurriculum.programme.prog_full_name'
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
                    'adm_refno',
                    'registration_number',
                    'student.surname',
                    'student.other_names',
                    'student.registration_date',
                    'progCurriculum.programme.prog_code',
                    'progCurriculum.programme.prog_full_name'
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
     * @throws \Exception
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = StudentProgCurriculum::find()->alias('sp')
            ->select([
                'sp.adm_refno',
                'sp.student_prog_curriculum_id',
                'sp.student_id',
                'sp.registration_number',
                'sp.prog_curriculum_id',
                'sp.student_category_id'
            ])
            ->joinWith(['student st' => function(ActiveQuery $q){
                $q->select([
                    'st.student_id',
                    'st.surname',
                    'st.other_names',
                    'st.registration_date'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['student.cohortHistory ch' => function(ActiveQuery $q){
                $q->select([
                    'ch.stud_id',
                    'ch.cohort_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['student.cohortHistory.cohort co' => function(ActiveQuery $q){
                $q->select([
                    'co.cohort_id',
                    'co.cohort_desc',
                    'co.adm_start_date',
                    'co.adm_end_date'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['progCurriculum curr' => function(ActiveQuery $q){
                $q->select([
                    'curr.prog_curriculum_id',
                    'curr.prog_id',
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['progCurriculum.programme prog' => function(ActiveQuery $q){
                $q->select([
                    'prog.prog_id',
                    'prog.prog_code',
                    'prog.prog_full_name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['studentCategory cat' => function(ActiveQuery $q){
                $q->select([
                    'cat.std_category_id',
                    'cat.std_category_name'
                ]);
            }], true, 'INNER JOIN')
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

        $query->andFilterWhere(['sp.adm_refno' => $this->adm_refno]);
        $query->andFilterWhere(['like', 'sp.registration_number', $this->registration_number]);
        $query->andFilterWhere(['like', 'st.surname', $this->getAttribute('student.surname')]);
        $query->andFilterWhere(['like', 'st.other_names', $this->getAttribute('student.other_names')]);
        $query->andFilterWhere(['like', 'prog.prog_code', $this->getAttribute('progCurriculum.programme.prog_code')]);
        $query->andFilterWhere(['like', 'prog.prog_full_name', $this->getAttribute('progCurriculum.programme.prog_full_name')]);

        if(!empty($params['RegisteredStudentsSearch']['student.registration_date'])){
            $registrationDate = $params['RegisteredStudentsSearch']['student.registration_date'];
            $registrationDateStart = new DateTime(substr($registrationDate, 0,10));
            $registrationDateEnd = new DateTime(substr($registrationDate, 13));
            $query->andFilterWhere(['between', 'st.registration_date', $registrationDateStart->format('Y-m-d'),
                $registrationDateEnd->format('Y-m-d')]);
        }

        $query->orderBy(['sp.adm_refno' => SORT_ASC]);

        return $dataProvider;
    }
}
