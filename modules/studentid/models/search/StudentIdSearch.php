<?php

namespace app\modules\studentid\models\search;

use app\modules\studentid\models\StudentId;
use app\modules\studentid\models\StudentIdStatus;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\db\Exception;

/**
 * app\modules\studentid\models\search\StudentIdSearch represents the model behind the search form about `app\modules\studentid\models\StudentId`.
 */
class StudentIdSearch extends StudentId
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id_serial_no', 'student_prog_curr_id', 'barcode', 'request_id'], 'integer'],
            [['issuance_date', 'valid_from', 'valid_to', 'id_status'], 'safe'],
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
     * @param string $studentIdStatus
     * @return SqlDataProvider
     * @throws Exception
     */
    public function search(array $params, string $studentIdStatus = StudentIdStatus::ID_READY): SqlDataProvider
    {
        $this->load($params);

        $count = self::getDb()->createCommand('SELECT COUNT(*) FROM smis.sm_student_id')->queryScalar();

        $query = <<<STUDENT_ID
SELECT
	smis.sm_student_id.student_id_serial_no,
	smis.sm_student_id.student_prog_curr_id,
	smis.sm_student_id.issuance_date,
	smis.sm_student_id.valid_from,
	smis.sm_student_id.valid_to,
	smis.sm_student_id.printing_date,
	smis.sm_student_id.barcode,
	smis.sm_student_id.id_status,
	smis.sm_student_programme_curriculum.registration_number,
	smis.sm_student_programme_curriculum.prog_curriculum_id,
	smis.sm_student_programme_curriculum.student_category_id,
	smis.sm_student_category.std_category_name,
	concat(smis.sm_student.surname,' ',smis.sm_student.other_names) as full_name,
	smis.org_programmes.prog_code,
	smis.org_programmes.prog_short_name,
	smis.org_programmes.prog_full_name
FROM
	smis.sm_student_id
	INNER JOIN smis.sm_student_programme_curriculum ON smis.sm_student_id.student_prog_curr_id = smis.sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN smis.org_programme_curriculum ON smis.sm_student_programme_curriculum.prog_curriculum_id = smis.org_programme_curriculum.prog_curriculum_id
	INNER JOIN smis.org_programmes ON smis.org_programme_curriculum.prog_id = smis.org_programmes.prog_id
	INNER JOIN smis.sm_student ON smis.sm_student_programme_curriculum.student_id = smis.sm_student.student_id
	INNER JOIN smis.sm_student_category ON smis.sm_student_programme_curriculum.student_category_id = smis.sm_student_category.std_category_id
STUDENT_ID;

        $filters = [];

        $filters[] = "smis.sm_student_id.id_status =  '$studentIdStatus'";


        if (count($filters) == 1) {
            $query .= ' WHERE ' . implode($filters);
        } elseif (count($filters) > 1) {
            $query .= ' WHERE ' . implode(' AND ', $filters);
        }

        return new SqlDataProvider([
            'sql' => $query,
            'db' => self::getDb(),
            'key' => 'student_id_serial_no',
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 15
            ]
        ]);
    }
}
