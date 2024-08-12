<?php

namespace app\modules\studentRecords\models\search;

use app\models\search\StudentSearch;
use PhpParser\Node\Expr\Cast\Object_;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Student;
use yii\db\Expression;

/**
 * StudentSearch represents the model behind the search form of `app\models\Student`.
 */
class StudentSearchUtility extends StudentSearch
{
    public $names;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'sponsor'], 'integer'],
            [['student_number', 'names',
//                'gender', 'country_code',
                'id_no', 'passport_no',
                'service_number',], 'safe'],
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
    public function search($params,$extras=[])
    {
        $query = Student::find();

        $prms = (Object)$extras;
        /*
        if(!empty($prms->college)){
            $query->innerJoinWith([
                'studentProgrammeCurriculums as SPC'
                ,'studentProgrammeCurriculums.progCurriculum as PC'
                ,'studentProgrammeCurriculums.progCurriculum.orgProgCurrUnits as PCU'
                ,'studentProgrammeCurriculums.progCurriculum.orgProgCurrUnits.orgUnitHistory as UH'
                ,'studentProgrammeCurriculums.progCurriculum.orgProgCurrUnits.orgUnitHistory.orgType as UT'
                ,'studentProgrammeCurriculums.progCurriculum.orgProgCurrUnits.orgUnitHistory.orgUnit as U'
            ]);
            $query->andWhere(['U.unit_code'=>$prms->college])
                ->andWhere(['UT.unit_type_name'=>'COLLEGE'])
                ;
        } */
        if(!empty($prms->programme)){
            $query->innerJoinWith([
                'studentProgrammeCurriculums as SPC'
                ,'studentProgrammeCurriculums.progCurriculum as PC'
                ,'studentProgrammeCurriculums.progCurriculum.prog as P'
            ]);
            $query->andWhere(['P.prog_id'=>$prms->programme])
                ;
        }
        if(!empty($prms->session)){
            $query->innerJoinWith([
                'studentProgrammeCurriculums as SPC'
                ,'studentProgrammeCurriculums.progCurriculum as PC'
                ,'studentProgrammeCurriculums.progCurriculum.orgProgCurrSemesters as PCS'
                ,'studentProgrammeCurriculums.progCurriculum.orgProgCurrSemesters.acadSessionSemester as ASS'
                ,'studentProgrammeCurriculums.progCurriculum.orgProgCurrSemesters.acadSessionSemester.acadSession as AS'
            ]);
            $query->andWhere(['AS.acad_session_id'=>$prms->session])
                ;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['names'] = [
            'asc' => ['surname' => SORT_ASC,],
            'desc' => ['surname' => SORT_DESC,],
        ];

        $this->load($params);

        if ((!$this->load($params) && $this->validate())
            && (empty($prms->session)&&empty($prms->programme))
        ) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'student_id' => $this->student_id,
            // 'dob' => $this->dob,
            'sponsor' => $this->sponsor,
            'registration_date' => $this->registration_date,
        ]);

        // If wildcard present
        if(str_contains($this->student_number,'%')){
            $query->andFilterWhere(['ilike', 'student_number', new Expression("'".$this->student_number."'")]);
        }else{
            $query->andFilterWhere(['ilike', 'student_number', $this->student_number]);
        }

        $query->andFilterWhere(
            ['or',['ilike', 'surname', $this->names],['ilike', 'other_names', $this->names]])
            ->andFilterWhere(['ilike', 'id_no', $this->id_no])
            ->andFilterWhere(['ilike', 'passport_no', $this->passport_no])
            ->andFilterWhere(['ilike', 'service_number', $this->service_number])
            ->andFilterWhere(['ilike', 'blood_group', $this->blood_group]);

        return $dataProvider;
    }
}
