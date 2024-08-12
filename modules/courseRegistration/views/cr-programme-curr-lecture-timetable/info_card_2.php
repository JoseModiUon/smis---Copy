<?php

use app\models\OrgAcademicLevels;
use app\models\OrgAcademicSession;
use app\models\OrgProgLevel;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgSemesterCode;
use app\models\OrgStudyCentre;
use app\models\OrgStudyGroup;


$acadSession = OrgAcademicSession::findOne($params['acad_session_id'])->acad_session_name;
$curriculum = OrgProgrammeCurriculum::findOne($params['prog_curriculum_id'])->prog_curriculum_desc;
$studyGroup = OrgStudyGroup::findOne($params['study_group_id'])->study_group_name;
$studyCenter = OrgStudyCentre::findOne($params['study_centre_id'])->study_centre_name;
$semester = OrgSemesterCode::findOne($params['semester_code'])->semster_name;
$programmeLevel = OrgProgLevel::findOne($params['programme_level_id'])->programme_level_name;
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