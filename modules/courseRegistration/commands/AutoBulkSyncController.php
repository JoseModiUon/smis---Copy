<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/15/2023
 * @time: 11:37 AM
 */

namespace app\modules\courseRegistration\commands;

use app\models\CrClassGroups;
use app\models\CrCourseCategory;
use app\models\CrProgCurrTimetable;
use app\models\CrProgrammeCurrLectureTimetable;
use app\models\ExGradingSystem;
use app\models\ExGradingSystemDetail;
use app\models\ExMode;
use app\models\OrgAcademicSession;
use app\models\OrgAcademicSessionSemester;
use app\models\OrgCourses;
use app\models\OrgProgCategory;
use app\models\OrgProgCurrCourse;
use app\models\OrgProgCurrSemester;
use app\models\OrgProgCurrSemesterGroup;
use app\models\OrgProgLevel;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgProgrammes;
use app\models\OrgProgType;
use app\models\OrgSemesterCode;
use app\models\OrgSemesterType;
use app\models\OrgStudyCentre;
use app\models\OrgStudyGroup;
use app\models\OrgUnitCourse;
use app\models\OrgUnitHead;
use app\models\OrgUnitHistory;
use app\models\OrgUnitTypes;
use app\models\OrgStudyCentreGroup;
use app\models\SmWithdrawalType;
use app\models\OrgUnit;
use app\modules\studentRegistration\helpers\SmisHelper;
use Exception;
use Yii;
use app\modules\setup\utils\AutoSynchronize;
use yii\console\Controller;
use app\models\OrgAcademicLevels;
use app\models\SmAcademicProgress;
use app\models\SmStudentSemesterSessionStatus;
use app\models\SmAcademicProgressStatus;
use app\models\SmStudentProgrammeCurriculum;
use app\models\SmStudentSemSessionProgress;
use app\modules\courseRegistration\traits\CrProgCurrTimetableTrait;

class AutoBulkSyncController extends Controller
{
    use CrProgCurrTimetableTrait;
    /**
     * @throws Exception
     */
    public function syncHelper(string $table)
    {


        $name = explode('.', $table::tableName());
        $name = $name[1];
        $admin = Yii::$app->db->beginTransaction();
        $portal = Yii::$app->db2->beginTransaction();
        SmisHelper::logMessage("{$name} sync started.", __METHOD__);
        try {

            $auto = new AutoSynchronize();
            $auto->bulkSync($table);
            $admin->commit();
            $portal->commit();
            SmisHelper::logMessage("{$name} sync finished.", __METHOD__);
        } catch (Exception $ex) {
            $portal->rollBack();
            $admin->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            SmisHelper::logMessage($message, __METHOD__, 'error');
        }
    }

    public function actionSync()
    {

        $tables = [
            CrClassGroups::class,
            CrCourseCategory::class,
            OrgProgCategory::class,
            OrgProgType::class,
            OrgProgrammes::class,
            ExGradingSystem::class,
            OrgProgrammeCurriculum::class,
            OrgAcademicLevels::class,
            OrgCourses::class,
            OrgProgCurrCourse::class,
            OrgAcademicSession::class,
            OrgSemesterCode::class,
            OrgSemesterType::class,
            OrgAcademicSessionSemester::class,

            OrgProgCurrSemester::class,
            OrgStudyCentre::class,
            OrgStudyGroup::class,
            OrgStudyCentreGroup::class,
            OrgProgLevel::class,
            OrgProgCurrSemesterGroup::class,
            ExMode::class,
            // CrProgCurrTimetable::class,
            // CrProgrammeCurrLectureTimetable::class,

            // OrgUnit::class,
            // // OrgUnitHead::class,
            // OrgUnitTypes::class,
            // OrgUnitHistory::class,


            // SmStudentSemesterSessionStatus::class,

            // SmAcademicProgressStatus::class,
            // // SmStudentProgrammeCurriculum::class,
            // SmAcademicProgress::class,
            // SmStudentSemSessionProgress::class,

        ];

        foreach ($tables as $table) {
            $this->syncHelper($table);
        }
    }


    public function regenerateIds()
    {
        $records  = CrProgCurrTimetable::find()->all();
        $newMarksheets = [];

        foreach ($records as $rec) {
            $rec->mrksheet_id = $this->mrksheetid($rec->timetable_id);
            // $newMarksheets[] = [$rec->timetable_id => $rec->mrksheet_id];
            if (!$rec->save()) {
                print_r($rec->getErrors());
            } else {
                echo "$rec->timetable_id updated" . PHP_EOL;
            }
        }
    }
}
