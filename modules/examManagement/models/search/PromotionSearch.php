<?php

namespace app\modules\examManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmExamResult;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * PromotionSearch represents the model behind the search form of `app\models\SmExamResult`.
 */
class PromotionSearch extends SmExamResult
{
    public $exam_result_id;
    public $fk_registration_number;

    public $result;
    public $ext_result;
    public $level_of_study;
    public $total_marks;
    public $surname;
    public $other_names;
    public $registration_number;
    public $acad_session_name;
    public $acad_session_id;
    public $academic_level_name;
    public $academic_level_id;
    public $prog_curriculum_id;
    public $semester_code;
    public $semster_name;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'fk_registration_number', 'result', 'ext_result', 'level_of_study', 'total_marks',
                'surname', 'other_names', 'registration_number', 'acad_session_name', 'acad_session_id',
                'academic_level_id', 'prog_curriculum_id', 'semester_code', 'semster_name'
            ], 'safe'],
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
        $query = SmExamResult::find()
            ->select([
                'sm_exam_result.exam_result_id',
                'sm_exam_result.fk_registration_number',
                'sm_exam_result.result',
                'sm_exam_result.ext_result',
                'sm_exam_result.level_of_study',
                'sm_exam_result.total_marks',
                'sm_student.surname',
                'sm_student.other_names',
                'sm_student_programme_curriculum.registration_number',
                'org_academic_session.acad_session_name',
                'org_academic_session.acad_session_id',
                'org_academic_levels.academic_level_name',
                'org_academic_levels.academic_level_id',
                'org_programme_curriculum.prog_curriculum_id',
                'org_semester_code.semester_code',
                'org_semester_code.semster_name',
            ])
            ->joinWith(['studentProgCurriculum' => function (ActiveQuery $spc) {
                $spc->innerJoinWith(['progCurriculum', 'student']);
                $spc->innerJoinWith(['smAcademicProgresses' => function (ActiveQuery $sap) {
                    $sap->innerJoinWith(['acadSession' => function (ActiveQuery $as) {
                        $as->innerJoinWith(['orgAcademicSessionSemesters' => function (ActiveQuery $oass) {
                            $oass->innerJoinWith(['semesterCode']);
                        }]);
                    }]);
                    $sap->innerJoinWith(['academicLevel']);
                }]);
            }])
            ->distinct()
            ->asArray();

        // dd($query->createCommand()->rawSql);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 20
            ]
        ]);


        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'org_academic_levels.academic_level_id' => $this->academic_level_id,
            'org_academic_session.acad_session_id' => $this->acad_session_id,
            'org_programme_curriculum.prog_curriculum_id' => $this->prog_curriculum_id,
            'org_semester_code.semester_code' => $this->semester_code

        ]);

        $query->andFilterWhere(['ilike', 'fk_registration_number', $this->fk_registration_number])
            ->andFilterWhere(['ilike', 'result', $this->result])
            ->andFilterWhere(['ilike', 'ext_result', $this->ext_result])
            ->andFilterWhere(['ilike', 'levelTrail', $this->levelTrail]);

        return $dataProvider;
    }
}
