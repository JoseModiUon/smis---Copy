<?php

namespace app\modules\studentid\commands;

use app\models\portal\SmisportalSmStudentId;
use app\models\portal\SmisportalSmStudentIdRequest;
use app\models\portal\SmisportalSmStudentIdStatus;
use app\models\SmStudentProgrammeCurriculum;
use app\modules\studentid\models\StudentId;
use app\modules\studentid\models\StudentIdDetails;
use app\modules\studentid\models\StudentIdRequest;
use app\modules\studentid\models\StudentIdStatus;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class IdRequestController extends Controller
{
    public function actionIndex(): int
    {
        echo "Cron command line to process student id requests";
        return ExitCode::OK;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function actionNew(): int
    {

        $idRequests = StudentIdRequest::fetchIdInformationFromStudentPortal();

        Yii::info("Found " . count($idRequests) . " pending student id requests");

        $insertableRecords = [];
        foreach ($idRequests as $key => $idRequest) {
            $adminIdRequest = new StudentIdRequest();
            $adminIdRequest->load(['StudentIdRequest' => $idRequest]);

            //switch to correct prog curriculum id
            $progCurriculumId = ArrayHelper::getValue($idRequest, 'prog_curriculum_id');
            $studentId = ArrayHelper::getValue($idRequest, 'student_id');
            $regNumber = ArrayHelper::getValue($idRequest, 'reg_no');

            $adminCurriculumInfo = SmStudentProgrammeCurriculum::findOne([
                'registration_number' => $regNumber,
                'prog_curriculum_id' => $progCurriculumId,
                'student_id' => $studentId
            ]);
            if ($adminCurriculumInfo != null) {
                $adminIdRequest->student_prog_curr_id = $adminCurriculumInfo->student_prog_curriculum_id;
                $insertableRecords[] = $adminIdRequest;

                Yii::debug("Reg $regNumber id $studentId Prog $progCurriculumId \n");
                Yii::debug($adminCurriculumInfo->attributes);
                Yii::debug($adminIdRequest->attributes);
            }

        }

        if (empty($insertableRecords)) {
            Yii::info("No valid pending id card requests, exiting....");
            return ExitCode::NOINPUT;
        }


        Yii::info("Syncing " . count($insertableRecords) . " valid student id requests to admin");
        $smisTransaction = Yii::$app->db->beginTransaction();
        $portalTransaction = Yii::$app->db2->beginTransaction();
        $valid = false;

        $updateIds = [];
        foreach ($insertableRecords as $key => $insertableRecord) {
            /* @var $insertableRecord StudentIdRequest */
            $insertableRecord->student_id_sync_status = true;
            $valid = $insertableRecord->validate();
            if ($valid) {
                $insertableRecord->save();
                $updateIds[] = $insertableRecord->request_id;
            } else {
                Yii::error($insertableRecord->getErrors());
                $smisTransaction->rollBack();
                break;
            }
        }

        $valid = $this->updatePortalIdRequestStatus($updateIds);
        if ($valid) {
            Yii::info("Committing records");
            $smisTransaction->commit();
            $portalTransaction->commit();
            return ExitCode::OK;
        }

        Yii::info("Unable to sync pending id requests");
        return ExitCode::DATAERR;
    }


    /**
     * @return int
     * @throws Exception
     */
    public function actionExpired(): int
    {
        //fetch all expired ids withing an interval of 1 year
        $now = new Expression('NOW()');
        $oneYearAgo = new Expression("NOW() - INTERVAL '1 year'");

        $expiredStudentIds = SmisportalSmStudentId::find()
            ->where(['id_status' => StudentIdStatus::ID_ACTIVE])
            ->andWhere(['<', 'valid_to', $now])
            ->andWhere(['>=', 'valid_from', $oneYearAgo])
            ->all();

        if (empty($expiredStudentIds)) {
            Yii::info("No expired id card found, exiting....");
            return ExitCode::NOINPUT;
        }

        Yii::info("Processing " . count($expiredStudentIds) . " expired student ids");

        $smisTransaction = Yii::$app->db->beginTransaction();
        $portalTransaction = Yii::$app->db2->beginTransaction();

        $idSerialNumbers = [];
        $valid = false;
        foreach ($expiredStudentIds as $key => $studentId) {
            /* @var $studentId SmisportalSmStudentId */
            $studentId->id_status = StudentIdStatus::ID_EXPIRED;
            $idSerialNumbers[] = $studentId->student_id_serial_no;
            $valid = $studentId->save();
        }


        if ($valid) {
            $this->updateSmisIdStatus(
                updateAttributes: ['id_status' => StudentIdStatus::ID_EXPIRED],
                conditionFields: ['barcode' => $idSerialNumbers]);

            $this->updateSmisIdDetailStatus(
                updateAttributes: ['student_id_status' => StudentIdStatus::ID_REPORTED_LOST],
                conditionFields: ['student_id_serial_no' => $idSerialNumbers]);

            $this->updateSmisStudentIdStatus(
                updateAttributes: ['status_name' => StudentIdStatus::ID_REPORTED_LOST],
                conditionFields: ['student_id_serial_no' => $idSerialNumbers]);

            $this->updatePortalStudentIdStatus(
                updateAttributes: ['status_name' => StudentIdStatus::ID_REPORTED_LOST],
                conditionFields: ['student_id_serial_no' => $idSerialNumbers]);

            Yii::info("Successfully updated " . count($expiredStudentIds) . " expired id cards");
            $smisTransaction->commit();
            $portalTransaction->commit();

            return ExitCode::OK;
        }
        $smisTransaction->rollBack();
        $portalTransaction->rollBack();


        Yii::info("Unable to sync expired student id cards");
        return ExitCode::DATAERR;
    }


    /**
     * @return int
     * @throws Exception
     */
    public function actionLost(): int
    {
        $lostIdCards = SmisportalSmStudentId::find()
            ->where(['id_status' => StudentIdStatus::ID_LOST])
            ->all();

        if (empty($lostIdCards)) {
            Yii::warning("No lost id cards found, exiting....");
            return ExitCode::NOINPUT;
        }

        Yii::info("Processing " . count($lostIdCards) . " ID(s) reported as lost");

        $smisTransaction = Yii::$app->db->beginTransaction();
        $portalTransaction = Yii::$app->db2->beginTransaction();

        $idSerialNumbers = [];
        $valid = false;
        foreach ($lostIdCards as $key => $studentId) {
            /* @var $studentId SmisportalSmStudentId */
            $studentId->id_status = StudentIdStatus::ID_REPORTED_LOST;
            $idSerialNumbers[] = $studentId->student_id_serial_no;
            $valid = $studentId->save();
        }


        if ($valid) {
            $this->updateSmisIdStatus(
                updateAttributes: ['id_status' => StudentIdStatus::ID_REPORTED_LOST],
                conditionFields: ['student_id_serial_no' => $idSerialNumbers]);

            $this->updateSmisIdDetailStatus(
                updateAttributes: ['student_id_status' => StudentIdStatus::ID_REPORTED_LOST],
                conditionFields: ['student_id_serial_no' => $idSerialNumbers]);

            $this->updateSmisStudentIdStatus(
                updateAttributes: ['status_name' => StudentIdStatus::ID_REPORTED_LOST],
                conditionFields: ['student_id_serial_no' => $idSerialNumbers]);

            $this->updatePortalStudentIdStatus(
                updateAttributes: ['status_name' => StudentIdStatus::ID_REPORTED_LOST],
                conditionFields: ['student_id_serial_no' => $idSerialNumbers]);



            $smisTransaction->commit();
            $portalTransaction->commit();

            return ExitCode::OK;
        }

        $smisTransaction->rollBack();
        $portalTransaction->rollBack();


        return ExitCode::DATAERR;
    }

    /**
     * @param array $updateIds
     * @param bool $idStatus
     * @return bool
     */
    private function updatePortalIdRequestStatus(array $updateIds, bool $idStatus = true): bool
    {
        $count = SmisportalSmStudentIdRequest::updateAll([
            'student_id_sync_status' => $idStatus
        ], ['request_id' => $updateIds]);

        Yii::info("$count records updated in " . __METHOD__);

        return $count > 0;

    }

    /**
     * @param array $updateAttributes
     * @param array $conditionFields
     * @return void
     */
    private function updateSmisIdStatus(array $updateAttributes, array $conditionFields): void
    {
        $count = StudentId::updateAll(attributes: $updateAttributes, condition: $conditionFields);
        $methodName = $this->extractMethodName(__METHOD__);
        Yii::info(message: "$count id records updated with the following flags in " . $methodName);
        Yii::debug($updateAttributes);
    }

    /**
     * @param array $updateAttributes
     * @param array $conditionFields
     * @return void
     */
    private function updateSmisIdDetailStatus(array $updateAttributes, array $conditionFields): void
    {
        $count = StudentIdDetails::updateAll(attributes: $updateAttributes, condition: $conditionFields);
        $methodName = $this->extractMethodName(__METHOD__);
        Yii::info(message: "$count id records updated in " . $methodName);
        Yii::debug($updateAttributes);
    }

    /**
     * @param array $updateAttributes
     * @param array $conditionFields
     * @return void
     */
    private function updateSmisStudentIdStatus(array $updateAttributes, array $conditionFields): void
    {
        $count = StudentIdStatus::updateAll(attributes: $updateAttributes, condition: $conditionFields);
        $methodName = $this->extractMethodName(__METHOD__);
        Yii::info(message: "$count id records updated in " . $methodName);
        Yii::debug($updateAttributes);
    }

    /**
     * @param array $updateAttributes
     * @param array $conditionFields
     * @return void
     */
    private function updatePortalStudentIdStatus(array $updateAttributes, array $conditionFields): void
    {
        $count = SmisportalSmStudentIdStatus::updateAll(attributes: $updateAttributes, condition: $conditionFields);

        $methodName = $this->extractMethodName(__METHOD__);
        Yii::info(message: "$count id records updated in " . $methodName);
        Yii::debug($updateAttributes);
    }

    /**
     * @param string $methodName
     * @return bool|string
     */
    private function extractMethodName(string $methodName): bool|string
    {
        $parts = explode('::', $methodName);
        return end($parts);
    }
}
