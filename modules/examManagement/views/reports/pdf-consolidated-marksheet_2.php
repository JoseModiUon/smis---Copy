<?php

use app\models\OrgAcademicSession;
use app\models\OrgProgLevel;
use app\models\OrgProgrammeCurriculum;

$count_courses = 0;
$tot_marks = 0;

$acad_year = OrgAcademicSession::findOne($requestData['acad_session_id'])->acad_session_name;
$yos = OrgProgLevel::findOne($requestData['programme_level_id'])->programme_level_name;
$programme = OrgProgrammeCurriculum::find()->select(['prog_curriculum_id', 'concat(prog_code, \' \',prog_short_name) as desc'])
    ->innerJoinWith('prog')
    ->where(['prog_curriculum_id' => $requestData['prog_curriculum_id']])
    ->asArray()->one();
?>

<html>

<body>
    <table>
        <tr>
            <td>
                <h3 style="text-align: left;text-transform:uppercase; font-weight:bold">Consolidated Marksheet</h3>
            </td>
            <td></td>
            <td></td>
            <td>________________________________</td>
        </tr>
        <tr>
            <td> <span style="font-size: 11px;">Dated: <?= strtoupper(date('d-M-Y')) ?></span></td>
            <td></td>
            <td></td>
            <td> <span style="font-size: 11px; float:right">Dean/Director Signature</span></td>
        </tr>
        <tr>
            <td>
                <div><b>ACADEMIC YEAR: <?= $acad_year ?> <span>YEAR OF STUDY: <?= $yos ?></span></b></div>
            </td>
            <td></td>
            <td></td>
            <td>
                <span style="font-size: 11px; margin-top: -40px; float: right;">Faculty/Institute/School Stamp</span>
            </td>

        </tr>
        <tr>
            <td>
                <div><b>PROGRAME: <?= $programme['desc'] ?></b></div>

            </td>
        </tr>
    </table>




    <div class="container">
        <div style="margin-top: 50px;">
            <table class="table">
                <?php foreach ($data as $key => $row) : ?>
                    <tr>
                        <td class="td"><b><?= $key + 1 . '. ' . $row['student_number'] . ' ' . $row['name'] ?></b></td>
                        <td class="td" colspan="12"><b>RECOM:</b></td>
                    </tr>
                    <tr>

                        <?php foreach ($row as $key => $value) : ?>
                            <?php if (str_starts_with($key, 'course_code')) : ?>

                                <td class="td"><?= $value ?></td>
                            <?php $count_courses++;
                            endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php foreach ($row as $key => $value) : ?>

                            <?php if (str_starts_with($key, 'final_mark')) : ?>
                                <td class="td">
                                    <?= $value ?> |
                                    <?php $tot_marks += $value ?>
                                <?php elseif (str_starts_with($key, 'grade')) : ?>
                                    <?= $value ?>
                                </td>
                            <?php endif; ?>

                        <?php endforeach; ?>
                        <td class="td">&nbsp;</td>
                        <td class="td"><?= $count_courses ?></td>
                        <td class="td"><?= $tot_marks ?></td>
                        <td class="td"><?= number_format((float)$tot_marks / $count_courses, 2, '.', ''); ?></td>
                        <td class="td">RECOM</td>
                    </tr>

                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>

</html>