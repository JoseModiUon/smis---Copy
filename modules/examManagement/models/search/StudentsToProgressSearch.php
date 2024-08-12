<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/27/2024
 * @time: 12:38 PM
 */

namespace app\modules\examManagement\models\search;

use app\modules\studentRegistration\models\AcademicSession;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\UnprocessableEntityHttpException;

final class StudentsToProgressSearch extends AcademicSession
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
     * @param array $params
     * @return ActiveDataProvider
     * @throws UnprocessableEntityHttpException
     */
    public function search(array $params): ActiveDataProvider
    {
        $semesterId = $params['semesterId'];

        if(empty($semesterId)) {
            $semesterId = null;
        }

        $query = (new Query())
            ->select([
                'stud.student_number',
                'stud.surname',
                'stud.other_names',
                'oac.student_prog_curriculum_id'
            ])
            ->from('smis.sm_academic_progress oac')
            ->innerJoin('smis.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id=oac.student_prog_curriculum_id')
            ->innerJoin('smis.sm_student stud', 'stud.student_id=spc.student_id')
            ->innerJoin('smis.sm_student_sem_session_progress ssp', 'ssp.academic_progress_id=oac.academic_progress_id')
            ->innerJoin('smis.org_prog_curr_semester_group pcsg', 'pcsg.prog_curriculum_sem_group_id=ssp.prog_curriculum_semester_id')
            ->where(['pcsg.prog_curriculum_sem_group_id' => $semesterId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ]
        ]);

        $this->load($params['queryParams']);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'stud.student_number', $this->getAttribute('regNumber')]);
        $query->andFilterWhere(['like', 'stud.surname', $this->getAttribute('surname')]);
        $query->andFilterWhere(['like', 'stud.other_names', $this->getAttribute('otherNames')]);

        return $dataProvider;
    }
}