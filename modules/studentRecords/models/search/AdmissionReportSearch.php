<?php

namespace app\modules\studentRecords\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdmittedStudent;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgProgrammes;
use yii\db\ActiveQuery;

/**
 * AdmissionReportSearch represents the model behind the search form of `app\models\AdmittedStudent`.
 */
class AdmissionReportSearch extends AdmittedStudent
{
    /**
     * {@inheritdoc}
     */

    public $intake_name;

    public function rules()
    {
        return [
            [['adm_refno'], 'integer'],
            [['org_programmes.prog_full_name', 'intake_name'], 'safe'],
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
        $query = OrgProgrammes::find()->select([
            'org_programmes.prog_full_name'
        ])->innerJoinWith(['orgProgrammeCurriculums' => function (ActiveQuery $opc) {
            $opc->innerJoinWith(['smStudentProgrammeCurriculums' => function (ActiveQuery $spc) {
                $spc->innerJoinWith(['admRefno' => function (ActiveQuery $ar) {
                    $ar->innerJoinWith('intakeCode');
                }]);
            }]);
        }])
            ->distinct();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'adm_refno' => $this->adm_refno,
            'sm_intakes.intake_name' => $this->intake_name,
        ]);

        return $dataProvider;
    }
}
