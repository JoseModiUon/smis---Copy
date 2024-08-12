<?php

use app\models\OrgProgCurrUnit;
use yii\db\ActiveQuery;

$info = OrgProgCurrUnit::find()
    ->select(['unit_head_name', 'unit_name', 'unit_type_name'])
    ->innerJoinWith(['orgUnitHistory' => function (ActiveQuery $q) {
        $q->innerJoinWith(['unitHead', 'orgUnit', 'orgType']);
    }])
    ->where(['prog_curriculum_id' => $studentOrgData['prog_curriculum_id']])->asArray()->one();
$fac = ucwords($info['unit_type_name'] . ' Of ' . $info['unit_name']);
?>
<html>

<body>
    <p style="text-align: center; text-decoration: underline; font-weight:bold"><?= ucwords($info['unit_type_name'] . ' Of ' . $info['unit_name']) ?></p>
    <p style="text-align: center; text-decoration: underline; font-weight:bold">ACADEMIC TRANSCRIPT</p>
    <div class="container">
        <?php foreach ($gradingData as $level => $data) : ?>
            <div style="page-break-after: always;">
                <table style="margin-bottom: 50px;">
                    <tr>
                        <td colspan="3">Name of Student : <?= $studentOrgData['surname'] . ' ' . $studentOrgData['other_names'] ?></td>
                        <td colspan="3">Registration Number : <?= $studentOrgData['student_number'] ?></td>

                    </tr>
                    <tr>
                        <td colspan="3">Faculty/School/Institute : <?= $fac ?></td>
                        <td colspan="3">Registration Date: <?= date_format(date_create($studentOrgData['registration_date']), "dS-M-Y") ?></td>



                    </tr>
                    <tr>
                        <td colspan="3">Academic Session : <?= array_column($data, 'acad_session_name')[0] ?></td>
                        <td colspan="3">Level of Study : <?= array_column($data, 'programme_level_name')[0] ?></td>

                    </tr>
                </table>

                <div class="result-area">
                    <table class="table">
                        <tr>
                            <td><span style="text-decoration: underline;">COURSE CODE</span></td>
                            <td><span style="text-decoration: underline;">DESCRIPTIVE COURSE TITLE</span></td>
                            <td><span style="text-decoration: underline;">ACADEMIC HOURS</span></td>
                            <td><span style="text-decoration: underline;">GRADE</span></td>
                        </tr>
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><?= $row['course_code'] ?></td>
                                <td><?= $row['course_name'] ?></td>
                                <td><?= $row['academic_hours'] ?></td>
                                <td><?= $row['grade'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="recommendation-area">
                    <p>RECOMMENDATION</p>
                </div>

                <div class="grading-system-area" style="margin-bottom:20px">
                    <div style="font-style:italic; font-size: 13px;">KEY TO GRADING SYSTEM</div>
                    <table>
                        <tr>
                            <td colspan="3">A = 70 % - 100 % - EXCELLENT</td>
                        </tr>
                        <tr>
                            <td colspan="3"> B = 60 % - 69.99 % - GOOD</td>
                        </tr>
                        <tr>
                            <td colspan="3">C = 50 % - 59.99 % - SATISFACTORY</td>
                        </tr>
                        <tr>
                            <td colspan="3">D = 40 % - 49.99 % - PASS</td>
                        </tr>
                        <tr>
                            <td colspan="3"> E = 0 % - 39.99 % - FAIL</td>
                        </tr>
                        <tr>
                            <td colspan="3"> *Thesis/Dissertation is not graded</td>
                        </tr>
                    </table>
                </div>

                <div class="issuer">
                    <table>
                        <tr>
                            <td><b><u>Date of Issue</u></b>:<?= date('Y-m-d') ?></td>
                            <td colspan="2"></td>
                            <td>Signed ______________________</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td>Academic Registrar</td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>