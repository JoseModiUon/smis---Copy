<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models\search;

use app\modules\studentRegistration\models\AdmittedStudent;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class DocumentsSearch extends AdmittedStudent
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'intake.intake_code',
            'intakeSource.source_id',
            'category.std_category_id',
            'programme.prog_code'
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
                    'surname',
                    'other_names',
                    'primary_email',
                    'primary_phone_no',
                    'application_refno',
                    'intake.intake_code',
                    'intakeSource.source_id',
                    'category.std_category_id',
                    'programme.prog_code'
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
     */
    public function search(array $params, array $moreParams = []): ActiveDataProvider
    {
        $submissionStatus = $moreParams['submissionStatus'];
        $admissionStatus = $moreParams['admissionStatus'];

        $query = AdmittedStudent::find()->alias('as')
            ->select([
                'as.adm_refno',
                'as.surname',
                'as.other_names',
                'as.primary_email',
                'as.primary_phone_no',
                'as.application_refno',
                'as.source_id',
                'as.intake_code',
                'as.student_category_id',
                'as.uon_prog_code'
            ])
            ->where([
                'as.doc_submission_status' => $submissionStatus,
                'as.admission_status' => $admissionStatus
            ])
            ->joinWith(['intake in' => function(ActiveQuery $q){
                $q->select([
                    'in.intake_code',
                    'in.intake_name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['intakeSource src' => function(ActiveQuery $q){
                $q->select([
                    'src.source_id',
                    'src.source'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['category cat' => function(ActiveQuery $q){
                $q->select([
                    'cat.std_category_id',
                    'cat.std_category_name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programme prog' => function(ActiveQuery $q){
                $q->select([
                    'prog.prog_code',
                    'prog.prog_short_name'
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

        $query->andFilterWhere(['as.adm_refno' => $this->adm_refno]);
        $query->andFilterWhere(['like', 'as.surname', $this->surname]);
        $query->andFilterWhere(['like', 'as.other_names', $this->other_names]);
        $query->andFilterWhere(['like', 'as.primary_email', $this->primary_email]);
        $query->andFilterWhere(['like', 'as.primary_phone_no', $this->primary_phone_no]);
        $query->andFilterWhere(['as.application_refno' => $this->application_refno]);
        $query->andFilterWhere(['in.intake_code' => $this->getAttribute('intake.intake_code')]);
        $query->andFilterWhere(['src.source_id' => $this->getAttribute('intakeSource.source_id')]);
        $query->andFilterWhere(['cat.std_category_id' => $this->getAttribute('category.std_category_id')]);
        $query->andFilterWhere(['prog.prog_code' => $this->getAttribute('programme.prog_code')]);

        $query->orderBy(['as.adm_refno' => SORT_ASC]);

        return $dataProvider;
    }
}
