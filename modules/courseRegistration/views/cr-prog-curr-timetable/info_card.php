<?php

use app\models\CrProgCurrTimetable;
use app\models\OrgSemesterCode;
use yii\db\ActiveQuery;

$types = CrProgCurrTimetable::find()
    ->joinWith(['progCurriculumSemGroup' => function (ActiveQuery $r) {
        $r->joinWith(['progCurriculumSemester' => function (ActiveQuery $q) {
            $q->joinWith(['progCurriculum', 'orgSemesterType']);
            $q->joinWith(['acadSessionSemester' => function (ActiveQuery $a) {
                $a->joinWith('acadSession');
            }]);
        }]);
        $r->joinWith(['studyCentreGroup' => function (ActiveQuery $q) {
            $q->joinWith(['studyCentre', 'studyGroup']);
        }]);
        $r->joinWith(['programmeLevel']);
    }])
    ->joinWith(['orgProgCurrCourse' => function (ActiveQuery $q) {
        $q->joinWith(['course', 'progCurriculum']);
    }])
    ->where(['timetable_id' => $params['timetable_id'], 'org_courses.course_id' => $params['course_id']])
    ->one();
$acadSession = $types['progCurriculumSemGroup']['progCurriculumSemester']['acadSessionSemester']['acadSession']['acad_session_name'];
$curriculum = $types['progCurriculumSemGroup']['progCurriculumSemester']['progCurriculum']['prog_curriculum_desc'];
$studyGroup = $types['progCurriculumSemGroup']['studyCentreGroup']['studyGroup']['study_group_name'];
$studyCenter = $types['progCurriculumSemGroup']['studyCentreGroup']['studyCentre']['study_centre_name'];
$semester_code = $types['progCurriculumSemGroup']['progCurriculumSemester']['acadSessionSemester']['semester_code'];
$semester = OrgSemesterCode::findOne($semester_code)->semster_name;
$programmeLevel = $types['progCurriculumSemGroup']['programmeLevel']['programme_level_name'];
$course_name = $types['orgProgCurrCourse']['course']['course_name'];
?>

<div class="row mb-2 mt-2">
    <div class="col-md-4">
        <span style="font-weight:bold">Curriculum: &nbsp;</span><?= $curriculum ?><br>
    </div>
    <div class="col-md-4">
        <span style="font-weight:bold">Study Group: &nbsp;</span><?= $studyGroup ?><br>
    </div>
    <div class="col-md-4">
        <span style="font-weight:bold">Study Center: &nbsp;</span><?= $studyCenter ?><br>
    </div>
</div>
<div class="row mt-2 mb-2">
    <div class="col-md-4">
        <span style="font-weight:bold">Semester: &nbsp; </span><?= $semester ?><br>
    </div>
    <div class="col-md-4">
        <span style="font-weight:bold">Academic Session: &nbsp;</span><?= $acadSession ?><br>
    </div>
    <div class="col-md-4">
        <span style="font-weight:bold">Programme Level: &nbsp;</span><?= $programmeLevel ?><br>
    </div>
</div>

<div class="row mt-2 mb-2">
    <div class="col-md-4">
        <span style="font-weight:bold">Course: &nbsp; </span><?= $course_name ?><br>
    </div>
</div>