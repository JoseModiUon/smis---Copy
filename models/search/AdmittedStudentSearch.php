<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdmittedStudent;

/**
 * AdmittedStudentSearch represents the model behind the search form of `app\models\AdmittedStudent`.
 */
class AdmittedStudentSearch extends AdmittedStudent
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adm_refno', 'source_id', 'application_refno', 'intake_code', 'student_category_id'], 'integer'],
            [['kcse_index_no', 'kcse_year', 'primary_phone_no', 'alternative_phone_no', 'primary_email', 'alternative_email', 'post_code', 'post_address', 'town', 'kuccps_prog_code', 'uon_prog_code', 'national_id', 'birth_cert_no', 'passport_no', 'admission_status', 'password', 'primary_email_salt', 'secondary_email_salt', 'primary_email_verified_date', 'secondary_email_verified_date', 'surname', 'other_names', 'primary_phone_country_code', 'alternative_phone_country_code', 'gender', 'clearance_status', 'password_changed_date'], 'safe'],
            [['doc_submission_status'], 'boolean'],
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
        $query = AdmittedStudent::find();

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
            'adm_refno' => $this->adm_refno,
            'source_id' => $this->source_id,
            'application_refno' => $this->application_refno,
            'intake_code' => $this->intake_code,
            'student_category_id' => $this->student_category_id,
            'doc_submission_status' => $this->doc_submission_status,
            'primary_email_verified_date' => $this->primary_email_verified_date,
            'secondary_email_verified_date' => $this->secondary_email_verified_date,
            'password_changed_date' => $this->password_changed_date,
        ]);

        $query->andFilterWhere(['ilike', 'kcse_index_no', $this->kcse_index_no])
            ->andFilterWhere(['ilike', 'kcse_year', $this->kcse_year])
            ->andFilterWhere(['ilike', 'primary_phone_no', $this->primary_phone_no])
            ->andFilterWhere(['ilike', 'alternative_phone_no', $this->alternative_phone_no])
            ->andFilterWhere(['ilike', 'primary_email', $this->primary_email])
            ->andFilterWhere(['ilike', 'alternative_email', $this->alternative_email])
            ->andFilterWhere(['ilike', 'post_code', $this->post_code])
            ->andFilterWhere(['ilike', 'post_address', $this->post_address])
            ->andFilterWhere(['ilike', 'town', $this->town])
            ->andFilterWhere(['ilike', 'kuccps_prog_code', $this->kuccps_prog_code])
            ->andFilterWhere(['ilike', 'uon_prog_code', $this->uon_prog_code])
            ->andFilterWhere(['ilike', 'national_id', $this->national_id])
            ->andFilterWhere(['ilike', 'birth_cert_no', $this->birth_cert_no])
            ->andFilterWhere(['ilike', 'passport_no', $this->passport_no])
            ->andFilterWhere(['ilike', 'admission_status', $this->admission_status])
            ->andFilterWhere(['ilike', 'password', $this->password])
            ->andFilterWhere(['ilike', 'primary_email_salt', $this->primary_email_salt])
            ->andFilterWhere(['ilike', 'secondary_email_salt', $this->secondary_email_salt])
            ->andFilterWhere(['ilike', 'surname', $this->surname])
            ->andFilterWhere(['ilike', 'other_names', $this->other_names])
            ->andFilterWhere(['ilike', 'primary_phone_country_code', $this->primary_phone_country_code])
            ->andFilterWhere(['ilike', 'alternative_phone_country_code', $this->alternative_phone_country_code])
            ->andFilterWhere(['ilike', 'gender', $this->gender])
            ->andFilterWhere(['ilike', 'clearance_status', $this->clearance_status]);

        return $dataProvider;
    }
}
