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
    <div style="position: relative;">
        CONSOLIDATED MARKSHEET<span style="float:right">_____________________________________</span>

    </div>
    <table>
        <tr>
            <td width="500px;">Hello</td>
            <td width="90px">World</td>
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