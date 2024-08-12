<?php

namespace app\modules\studentRecords\commands;

use app\models\portal\SmisportalSmStudentIdRequest;
use app\models\SmStudentProgrammeCurriculum;
use app\modules\studentid\models\StudentIdRequest;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class DefermentSync extends Controller
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

        $idRequests = StudentIdRequest::fetchPendingRequestFromStudentPortal();

        Yii::info("Syncing " . count($idRequests) . " pending student id requests");

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

        $valid = $this->updatePortalIdStatus($updateIds);
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
     * @param array $updateIds
     * @param bool $idStatus
     * @return bool
     */
    private function updatePortalIdStatus(array $updateIds, $idStatus = true): bool
    {
        $count = SmisportalSmStudentIdRequest::updateAll([
            'student_id_sync_status' => $idStatus
        ], ['request_id' => $updateIds]);

        Yii::info("$count records updated");

        return $count > 0;
    }
}
