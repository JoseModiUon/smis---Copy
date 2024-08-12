<?php

namespace app\modules\studentid\models\search;

use app\modules\studentid\models\IdRequestStatus;
use app\modules\studentid\models\StudentIdRequest;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

/**
 * app\modules\studentid\models\search\StudentIdRequestSearch
 * represents the model behind the search form about
 * `app\modules\studentid\models\StudentIdRequest`.
 */
class StudentIdRequestSearch extends StudentIdRequest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'request_type_id',
                'student_prog_curr_id', 'status_id',
                'prog_curriculum_id', 'student_category_id'], 'integer'],
            [['request_date', 'prog_type_name', 'reg_no'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = StudentIdRequest::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'request_id' => $this->request_id,
            'request_type_id' => $this->request_type_id,
            'student_prog_curr_id' => $this->student_prog_curr_id,
            'request_date' => $this->request_date,
            'status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'source', $this->source]);

        return $dataProvider;
    }

    /**
     * @param mixed $params
     * @param string $idRequestStatus
     * @return SqlDataProvider
     * @throws \yii\db\Exception
     */
    public function searchIdRequests(mixed $params, string $idRequestStatus = IdRequestStatus::PENDING): SqlDataProvider
    {


        $this->load($params);

        $count = self::getDb()->createCommand('SELECT COUNT(*) FROM smis.sm_student_id_request')->queryScalar();


        $query = <<<SQL
SELECT
	smis.sm_student_id_request.request_id,
	smis.sm_student_id_request.request_type_id,
	smis.sm_id_request_type.id_type_desc AS id_request_type,
	smis.sm_student_id_request.student_prog_curr_id,
	smis.sm_student_id_request.request_date,
	smis.sm_student_id_request.status_id,
	smis.sm_student_id_request."source" AS request_reason,
	smis.sm_student_programme_curriculum.registration_number AS reg_no,
	smis.sm_student_programme_curriculum.prog_curriculum_id,
	smis.sm_student_programme_curriculum.student_category_id,
	smis.sm_student_category.std_category_name,
	smis.org_programmes.prog_code,
	smis.org_programmes.prog_full_name,
	smis.org_programmes.prog_type_id,
	smis.org_programmes.prog_cat_id,
	smis.org_prog_type.prog_type_code,
	smis.org_prog_type.prog_type_name,
	concat ( smis.sm_student.surname, ' ', smis.sm_student.other_names ) AS full_name,
	smis.sm_id_request_status.status_name as id_request_status
FROM
	smis.sm_student_id_request
	INNER JOIN smis.sm_student_programme_curriculum ON smis.sm_student_id_request.student_prog_curr_id = smis.sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN smis.org_programme_curriculum ON smis.sm_student_programme_curriculum.prog_curriculum_id = smis.org_programme_curriculum.prog_curriculum_id
	INNER JOIN smis.org_programmes ON smis.org_programme_curriculum.prog_id = smis.org_programmes.prog_id
	INNER JOIN smis.sm_student_category ON smis.sm_student_programme_curriculum.student_category_id = smis.sm_student_category.std_category_id
	INNER JOIN smis.org_prog_type ON smis.org_programmes.prog_type_id = smis.org_prog_type.prog_type_id
	INNER JOIN smis.sm_student ON smis.sm_student_programme_curriculum.student_id = smis.sm_student.student_id
	INNER JOIN smis.sm_id_request_type ON smis.sm_student_id_request.request_type_id = smis.sm_id_request_type.request_type_id
	INNER JOIN smis.sm_id_request_status ON smis.sm_student_id_request.status_id = smis.sm_id_request_status.status_id
SQL;

        $requestStatusIds = IdRequestStatus::getStatusId($idRequestStatus);


        $filters = [];

        if (count($requestStatusIds) > 0) {
            $ids = implode(',', $requestStatusIds);
            $filters[] = "smis.sm_student_id_request.status_id IN ($ids)";
        }

        $filters[] = "smis.sm_student_programme_curriculum.registration_number LIKE '%$this->reg_no%'";

        if ($this->reg_no) {
            $filters[] = "smis.sm_student_programme_curriculum.registration_number LIKE '%$this->reg_no%'";
        }

        if ($this->student_category_id) {
            $filters[] = "smis.sm_student_programme_curriculum.student_category_id = $this->student_category_id";
        }

        if ($this->prog_curriculum_id) {
            $filters[] = "smis.sm_student_programme_curriculum.prog_curriculum_id = $this->prog_curriculum_id";
        }

        if (count($filters) == 1) {
            $query .= ' WHERE ' . implode($filters);
        } elseif (count($filters) > 1) {
            $query .= ' WHERE ' . implode(' AND ', $filters);
        }
        return new SqlDataProvider([
            'sql' => $query,
            'db' => self::getDb(),
            'key' => 'request_id',
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 15
            ]
        ]);
    }
}
