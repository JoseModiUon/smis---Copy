<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/18/2023
 * @time: 10:22 AM
 */
declare(strict_types=1);
namespace app\modules\courseRegistration\models\search;

use app\modules\studentRegistration\models\StudentSemesterSessionProgress;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

final class StudentsInSessionSearch extends StudentSemesterSessionProgress
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'regNumber',
            'surname',
            'otherNames'
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
                    'regNumber',
                    'surname',
                    'otherNames'
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
                'st.student_number',
                'st.surname',
                'st.other_names',
                'prog.prog_code'
            ])
            ->from(SMIS_DB_SCHEMA . '.sm_student_sem_session_progress sp')
            ->innerJoin(SMIS_DB_SCHEMA . '.sm_academic_progress ap', 'ap.academic_progress_id = sp.academic_progress_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = ap.student_prog_curriculum_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.sm_student st', 'st.student_id = spc.student_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programme_curriculum pc', 'pc.prog_curriculum_id = spc.prog_curriculum_id')
            ->innerJoin(SMIS_DB_SCHEMA . '.org_programmes prog', 'prog.prog_id = pc.prog_id')
            ->where(['sp.prog_curriculum_semester_id' => $params['progCurrSemGroupId']])
            ->andWhere(['not', ['sp.registration_date' => null]]);

            if (!empty($params['progCode'])) {
                $query->andWhere(['prog.prog_code' => $params['progCode']]);
            }

            $query->orderBy(['st.student_number' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false
        ]);

        $this->load($params['queryParams']);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'st.student_number', $this->getAttribute('regNumber')]);
        $query->andFilterWhere(['like', 'st.surname', $this->getAttribute('surname')]);
        $query->andFilterWhere(['like', 'st.other_names', $this->getAttribute('otherNames')]);

        return $dataProvider;
    }
}