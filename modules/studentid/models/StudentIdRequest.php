<?php

namespace app\modules\studentid\models;

use app\models\SmStudentIdRequest;
use Yii;
use yii\db\Connection;
use yii\db\DataReader;
use yii\db\Exception;

class StudentIdRequest extends SmStudentIdRequest
{


    public $prog_type_name;
    public $prog_curriculum_id;
    public $student_category_id;
    public $reg_no;

    /**
     * @param int $pk
     * @return array|DataReader|null
     * @throws Exception
     */
    public static function findOneByPk(int $pk): DataReader|array|null
    {
        $query = self::baseSmisQuery();

        $query .= <<<FILTER
            WHERE sm_student_id_request.request_id = :requestId
FILTER;

        $query = self::getDb()->createCommand($query);
        $data = $query->bindValues(['requestId' => $pk])->queryOne();
        if (!$data) {
            return null;
        }
        return $data;
    }

    private static function baseSmisQuery(): string
    {
        return <<<SQL
SELECT
	sm_student_id_request.request_id,
	sm_student_id_request.student_prog_curr_id,
	sm_student_id_request.request_date,
	sm_student_id_request.status_id,
	sm_student_programme_curriculum.registration_number AS reg_no,
	sm_student_programme_curriculum.prog_curriculum_id,
	sm_student_category.std_category_name,
	org_programmes.prog_code,
	org_programmes.prog_short_name,
	org_programmes.prog_full_name,
	org_programmes.prog_type_id,
	org_programmes.prog_cat_id,
	org_prog_type.prog_type_code,
	org_prog_type.prog_type_name,
	sm_student.student_number,
	concat(sm_student.surname,' ',sm_student.other_names) as full_name,
	COALESCE ( sm_student.id_no, sm_student.passport_no ) AS id_pp,
	org_programme_curriculum.start_date,
	org_programme_curriculum.end_date
FROM
	smis.sm_student_id_request
	INNER JOIN smis.sm_student_programme_curriculum ON sm_student_id_request.student_prog_curr_id = sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN smis.org_programme_curriculum ON sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
	INNER JOIN smis.org_programmes ON org_programme_curriculum.prog_id = org_programmes.prog_id
	INNER JOIN smis.sm_student_category ON sm_student_programme_curriculum.student_category_id = sm_student_category.std_category_id
	INNER JOIN smis.org_prog_type ON org_programmes.prog_type_id = org_prog_type.prog_type_id
	INNER JOIN smis.sm_student ON sm_student_programme_curriculum.student_id = sm_student.student_id
SQL;
    }

    /**
     * @param int $currProgId
     * @return DataReader|array|null
     * @throws Exception
     */
    public static function findOneByCurrProgId(int $currProgId, int $statusId): DataReader|array|null
    {
        $query = self::baseSmisQuery();

        $query .= <<<FILTER
            WHERE sm_student_id_request.student_prog_curr_id = :currProgId
            AND sm_student_id_request.status_id = :statusId
FILTER;

        $query = self::getDb()->createCommand($query);
        $data = $query->bindValues([
            'currProgId' => $currProgId,
            'statusId' => $statusId
        ])->queryOne();
        if (!$data) {
            return null;
        }
        return $data;
    }

    /**
     * @param bool $syncStatus
     * @return array|DataReader|false
     * @throws Exception
     */
    public static function fetchIdInformationFromStudentPortal(bool $syncStatus = false): bool|DataReader|array
    {

        $schema = 'smisportal';
        $query = <<<SQL
SELECT
	sm_student_id_request.request_id,
	sm_student_id_request.student_prog_curr_id,
	sm_student_id_request.request_date,
	sm_student_id_request.status_id,
	sm_id_request_status.status_name,
	sm_student_id_request.request_type_id,
	sm_student_id_request.source,
	sm_student_id_request.student_id_sync_status,
	sm_student_programme_curriculum.registration_number AS reg_no,
	sm_student_programme_curriculum.student_id,
	sm_student_programme_curriculum.prog_curriculum_id,
	sm_student_category.std_category_name,
	org_programmes.prog_code,
	org_programmes.prog_short_name,
	org_programmes.prog_full_name,
	org_programmes.prog_type_id,
	org_programmes.prog_cat_id,
	org_prog_type.prog_type_code,
	org_prog_type.prog_type_name,
	sm_student.student_number,
	concat(sm_student.surname,' ',sm_student.other_names) as full_name,
	COALESCE ( sm_student.id_no, sm_student.passport_no ) AS id_pp,
	org_programme_curriculum.start_date,
	org_programme_curriculum.end_date
FROM
	$schema.sm_student_id_request
	INNER JOIN $schema.sm_student_programme_curriculum ON sm_student_id_request.student_prog_curr_id = sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN $schema.org_programme_curriculum ON sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
	INNER JOIN $schema.org_programmes ON org_programme_curriculum.prog_id = org_programmes.prog_id
	INNER JOIN $schema.sm_student_category ON sm_student_programme_curriculum.student_category_id = sm_student_category.std_category_id
	INNER JOIN $schema.org_prog_type ON org_programmes.prog_type_id = org_prog_type.prog_type_id
	INNER JOIN $schema.sm_student ON sm_student_programme_curriculum.student_id = sm_student.student_id
	INNER JOIN $schema.sm_id_request_status ON sm_student_id_request.status_id = sm_id_request_status.status_id
WHERE
    sm_student_id_request.student_id_sync_status = :syncStatus
SQL;

        if ($syncStatus === false) {
            $query = "$query OR sm_student_id_request.student_id_sync_status IS NULL";
        }


        /** @var Connection $db */
        $db = Yii::$app->db2;

        $query = $db->createCommand($query);

        return $query->bindValues([
            'syncStatus' => $syncStatus,
        ])->queryAll();
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [['request_id', 'reg_no', 'prog_type_name'], 'safe'];

        return $rules;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        $labels['request_type_id'] = 'Request type';
        $labels['student_prog_curr_id'] = 'Current programme';
        $labels['prog_full_name'] = 'Programme';
        $labels['std_category_name'] = 'Academic level';
        $labels['request_date'] = 'Request date';
        $labels['status_id'] = 'Request status';
        $labels['source'] = 'Request reason';

        $labels['student_category_id'] = $labels['std_category_name'];
        $labels['id_request_type'] = $labels['request_type_id'];
        $labels['prog_curriculum_id'] = $labels['prog_full_name'];
        return $labels;
    }

    public function attributeHints()
    {
        $hints = parent::attributeHints();

        $hints['source'] = 'Request reason';
        return $hints;
    }
}
