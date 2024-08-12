<?php

use app\models\ExGradingSystemDetail;
use app\models\OrgProgCurrUnit;
use app\models\OrgProgLevel;
use yii\db\ActiveQuery;
use app\models\SmExamResult;

$info = OrgProgCurrUnit::find()
    ->select(['unit_head_name', 'unit_name', 'unit_type_name'])
    ->innerJoinWith(['orgUnitHistory' => function (ActiveQuery $q) {
        $q->innerJoinWith(['unitHead', 'orgUnit', 'orgType']);
    }])
    ->where(['prog_curriculum_id' => $studentOrgData['prog_curriculum_id']])->asArray()->one();
$exGrading = ExGradingSystemDetail::find()
    ->joinWith(['gradingSystem' => function (ActiveQuery $r) {
        $r->joinWith(['programmeCurriculum']);
    }])
    ->where(['prog_curriculum_id' => $studentOrgData['prog_curriculum_id']])
    ->orderBy(['ex_grading_system_detail.grade' => SORT_ASC])
    ->all();

?>

<html>

<body>
    <p style="text-align: center; text-transform: uppercase; font-weight:bold"><?= ucwords($info['unit_name']) ?></p>

    <p style="text-align: center; text-transform:uppercase; font-weight:bold">Provisional Result Slip</p>
    <div class="container">
        <?php foreach ($gradingData as $level => $data) : ?>
            <div style="page-break-after: always;">
                <table style="margin-bottom: 50px;">
                    <tr>
                        <td colspan="3"><span style="font-weight:bold">Names:</span> <span style="font-size:10px"> <?= $studentOrgData['surname'] . ' ' . $studentOrgData['other_names'] ?></span></td>
                        <td colspan="3"><span style="font-weight:bold">Registration Number</span> : <span style="font-size:10px"> <?= $studentOrgData['student_number'] ?></span></td>

                    </tr>
                    <tr>
                        <td colspan="3"><span style="font-weight:bold">Degree Programme :</span> <span style="font-size:10px"> <?= $studentOrgData['prog_full_name'] ?></span></td>
                        <td colspan="3"><span style="font-weight:bold">Registration Date: </span> <span style="font-size:10px"> <?= date_format(date_create($studentOrgData['registration_date']), "dS-M-Y") ?></span></td>


                    </tr>
                    <tr>
                        <td colspan="3"><span style="font-weight:bold">Academic Session :</span> <span style="font-size:10px"> <?= $data[0]['acad_session_name'] ?></span></td>
                        <td colspan="3"><span style="font-weight:bold">Level of Study :</span> <span style="font-size:10px"> <?= OrgProgLevel::findOne($data[0]['level_of_study'])->programme_level_name; ?></span></td>

                    </tr>
                </table>

                <div class="result-area">
                    <table class="table">
                        <tr>
                            <td><span style="font-weight:bold">COURSE CODE</span></td>
                            <td><span style="font-weight:bold">DESCRIPTIVE COURSE TITLE</span></td>
                            <td><span style="font-weight:bold">GRADE</span></td>
                        </tr>
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td> <span style="font-size:10px"><?= $row['course_code'] ?></span></td>
                                <td> <span style="font-size:10px"><?= $row['course_name'] ?></span></td>
                                <td> <span style="font-size:10px"><?= $row['grade'] ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="recommendation-area">
                    <?php
                    $examResult = SmExamResult::find()->select(['result', 'ext_result'])->where(['level_of_study' => $level, 'fk_registration_number' => $studentOrgData['student_number']])->one();

            ?>
                    <p> <span style="font-weight:bold">RECOMMENDATION:</span> <span style="font-size:10px;"><?= $examResult->result ?? '' ?> - <?= $examResult->ext_result ?? '' ?></span></p>
                </div>

                <div class="grading-system-area" style="margin-bottom:20px">
                    <div style="font-style:italic; font-size: 11px;">KEY TO GRADING SYSTEM</div>
                    <table>
                        <?php foreach ($exGrading as $grading) : ?>
                            <tr>
                                <td colspan="3"><span style="font-size:10px"><?= $grading->grade ?> = <?= $grading->lower_bound ?> % - <?= $grading->upper_bound ?> % - <?= $grading->legend ?><span style="font-size:10px"></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="font-size:9px">
                                This is a provisional result slip issued by Faculties or Institutes or Schools of the University <br /> for the purposes of Employment and/or Sponsorship, and is not valid without a Stamp.
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="issuer">
                    <div>
                        Issuer: <?= $info['unit_head_name'] . ' ' . $info['unit_name'] ?> <br><span><b>Date of Issue</b>:<?= date('Y-m-d') ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>