<?php

namespace app\modules\courseRegistration\traits;

use app\models\CrProgCurrTimetable as ModelsCrProgCurrTimetable;
use app\models\CrProgrammeCurrLectureTimetable;
use app\models\ExMode;
use app\modules\courseRegistration\models\CrProgCurrTimetable;
use \app\modules\courseRegistration\models\CrProgrammeCurrLectureTimetable as PortalLectureTimetable;
use Yii;

trait CrProgCurrTimetableTrait
{
    /**
     * handles inserting into CrProgCurrTimetable and CrProgrammeCurrLectureTimetable
     *
     * @return boolean|array
     */
    public function processPost(): bool|array
    {
        $handleTimetables = $this->handleTimetables();
        if (!is_array($handleTimetables) && $handleTimetables == true) {
            return $this->handleLecTimetables();
        }
        return $handleTimetables;
    }

    public function handleLecTimetables(): bool|array
    {
        $lecTimetable = $this->request->post('CrProgrammeCurrLectureTimetable');
        $lecModel = new CrProgrammeCurrLectureTimetable();
        $lecPortalModel = new PortalLectureTimetable;

        if (array_key_exists('lecture_timetable_id', $lecTimetable)) {
            $lecModel = CrProgrammeCurrLectureTimetable::findOne($lecTimetable['lecture_timetable_id']);
            $lecPortalModel = PortalLectureTimetable::findOne($lecTimetable['lecture_timetable_id']);
        }

        $this->assign($lecModel, $lecTimetable);
        if ($lecModel->save()) {
            $lecModel->refresh();
            $this->assign($lecPortalModel, array_merge($lecTimetable, ['lecture_timetable_id' => $lecModel->lecture_timetable_id]));
            return $lecPortalModel->save() ?? ['lecPortalModel' => $lecPortalModel];
        }
        return ['lecModel' => $lecModel];
    }

    /**
     * handles inserting into CrProgCurrTimetable portal and admin
     * @return boolean|array
     */
    public function handleTimetables(): bool|array
    {

        $postTimeTable = $this->request->post('CrProgCurrTimetable');
        $timetableModel = ModelsCrProgCurrTimetable::findOne($postTimeTable['timetable_id']);
        
        $mrksheet_id = $this->mrksheetid($timetableModel->timetable_id);
        $postTimeTable['mrksheet_id'] = $mrksheet_id;

        $timetablePortalModel = CrProgCurrTimetable::findOne($postTimeTable['timetable_id']);
        if (!$timetablePortalModel) {
            $timetablePortalModel = $this->createPortalTimetable($timetableModel);
        }
        $this->assign($timetableModel, $postTimeTable);
        $this->assign($timetablePortalModel, $postTimeTable);
        return $timetableModel->save() && $timetablePortalModel->save() ? true : ['timetableModel' => $timetableModel, 'timetablePortalModel' => $timetablePortalModel];
    }

    /**
     * create record on CrProgCurrTimetable on portal
     *
     * @param ModelsCrProgCurrTimetable $model
     * @return CrProgCurrTimetable|false
     */
    public function createPortalTimetable(ModelsCrProgCurrTimetable $model): CrProgCurrTimetable|false
    {
        $portalTimetable = new CrProgCurrTimetable();
        $array = [];
        foreach ($model->attributes() as $att) {
            if (in_array($att, $portalTimetable->attributes())) {
                $array[$att] = $model->{$att};
            }
        }
        $this->assign($portalTimetable, $array);
        if ($portalTimetable->save()) {
            $portalTimetable->refresh();
            return $portalTimetable;
        }
        return false;
    }

    public function createTimetable($params)
    {
        $model = ModelsCrProgCurrTimetable::find()->where([
            "prog_curriculum_course_id" => $params['prog_curriculum_course_id'],
            "prog_curriculum_sem_group_id" => $params['prog_curriculum_sem_group_id']
        ])->one();

        if (!$model) {
            $model = new ModelsCrProgCurrTimetable();
        }
        $ex_mode = ExMode::find()->select('exam_mode_id as exam_mode')->where(['exam_mode_name' => 'PHYSICAL'])->asArray()->one();
        $this->assign($model, array_merge($params, $ex_mode));
        if($model->save()) {
            $model->refresh();
        } else {
            var_dump($model->getErrors());
        }

        return $model;
    }

    public function updatePublisherStatusColumn($timetableId): bool
    {
        $model = ModelsCrProgCurrTimetable::findOne($timetableId);
        $ok = false;
        if ($model) {
            $model->publish_status = 0;
            $ok = $model->save();
        }
        return $ok;
    }
    public function updateModPublisherStatusColumn($timetableId): bool
    {
        $model = ModelsCrProgCurrTimetable::findOne($timetableId);
        $ok = false;
        if ($model) {
            $model->publish_status = 2;
            $ok = $model->save();
        }
        return $ok;
    }
    /**
     * assign values to model dynamically
     * @param Object $model
     * @param array $params
     * @return void
     */
    public function assign(Object $model, array $params): void
    {
        foreach ($params as $key => $value) {
            if (in_array($key, $model->attributes())) {
                $model->{$key} = $value;
            }
        }
    }

    public function mrksheetid($timetableId) {
      
      $sql = "SELECT
        cr_prog_curr_timetable.timetable_id,
        org_academic_session.acad_session_name || '_' ||
        org_programmes.prog_code || '_' ||
        org_prog_curr_semester_group.programme_level || '_' ||
        org_academic_session_semester.semester_code || '_' ||
        org_study_group.study_group_id || '_' ||
        org_courses.course_code || '_' ||
        org_courses.course_id
        as marksheet_id
        FROM
        smis.cr_prog_curr_timetable
        INNER JOIN
        smis.org_prog_curr_course
        ON
        cr_prog_curr_timetable.prog_curriculum_course_id = org_prog_curr_course.prog_curriculum_course_id
        INNER JOIN
        smis.org_courses
        ON
        org_prog_curr_course.course_id = org_courses.course_id
        INNER JOIN
        smis.org_prog_curr_semester_group
        ON
        cr_prog_curr_timetable.prog_curriculum_sem_group_id = org_prog_curr_semester_group.prog_curriculum_sem_group_id
        INNER JOIN
        smis.org_study_centre_group
        ON
        org_prog_curr_semester_group.study_centre_group_id = org_study_centre_group.study_centre_group_id
        INNER JOIN
        smis.org_study_group
        ON
        org_study_centre_group.study_group_id = org_study_group.study_group_id
        INNER JOIN
        smis.org_prog_curr_semester
        ON
        org_prog_curr_semester_group.prog_curriculum_semester_id = org_prog_curr_semester.prog_curriculum_semester_id
        INNER JOIN
        smis.org_programme_curriculum
        ON
        org_prog_curr_semester.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
        INNER JOIN
        smis.org_programmes
        ON
        org_programme_curriculum.prog_id = org_programmes.prog_id
        INNER JOIN
        smis.org_academic_session_semester
        ON
        org_prog_curr_semester.acad_session_semester_id = org_academic_session_semester.acad_session_semester_id
        INNER JOIN
        smis.org_academic_session
        ON
        org_academic_session_semester.acad_session_id = org_academic_session.acad_session_id
        WHERE  cr_prog_curr_timetable.timetable_id = $timetableId
        ";


        return Yii::$app->db->createCommand($sql)->queryOne()['marksheet_id'];

    }
}
