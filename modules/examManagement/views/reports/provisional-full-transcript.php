<?php

use app\helpers\SmisHelper;
use app\models\ExGradingSystemDetail;
use app\models\OrgProgCurrUnit;
use app\models\OrgProgLevel;
use app\models\OrgUnit;
use yii\db\ActiveQuery;
use app\models\SmExamResult;

$info = OrgProgCurrUnit::find()
    ->select(['unit_head_name', 'unit_name', 'unit_type_name', 'parent_id'])
    ->innerJoinWith(['orgUnitHistory' => function (ActiveQuery $q) {
        $q->innerJoinWith(['unitHead', 'orgUnit', 'orgType']);
    }])
    ->where(['prog_curriculum_id' => $studentOrgData['prog_curriculum_id']])->asArray()->one();

$parent = OrgUnit::findOne($info['parent_id']);

$exGrading = ExGradingSystemDetail::find()
    ->joinWith(['gradingSystem' => function (ActiveQuery $r) {
        $r->joinWith(['programmeCurriculum']);
    }])
    ->where(['prog_curriculum_id' => $studentOrgData['prog_curriculum_id']])
    ->orderBy(['ex_grading_system_detail.lower_bound' => SORT_DESC])
    ->all();

?>
<html>

<body style="font-size: 16px; font-family: Arial Narrow, Arial, sans-serif;">

    <div class="container">
        <?php foreach ($gradingData as $level => $data) : ?>

            <div class="header" style="border-bottom: 2px solid black; text-align:center;padding-top: 120px;">
                <!-- <p style="text-align: center; padding-top:-40px; margin-top:-40px;"><img src="<?php // Yii::getAlias('@app') . '/web/images/ndu-logo-first.jpg'
                                                                                                    ?>" height="20%" width="20%"></p> -->

                <!-- <div style="text-align: center; text-transform: uppercase; font-weight:lighter; padding-top:-15px; margin-top:-15px;"><?= ucwords($parent->unit_name) ?></div>

                <div style="text-align: center; text-transform: uppercase; font-weight:lighter">
                    P.O. BOX 3812-20100 NAKURU, KENYA
                </div>
                <div style="text-align: center; font-weight:lighter">
                    Email: info@ndu.ac.ke
                </div> -->
                <p style="text-align: center; text-transform:uppercase; font-weight:bold;">Academic Transcript</p>


            </div>

            <div style="page-break-after: always; margin-top: 12px;">
                <table style="margin-bottom: 20px;">
                    <tr>
                        <td colspan="8"><span style="font-weight:bold;font-size:12px">STUDENT NAME</span></td>
                        <td>:</td>
                        <td><span style="font-size:10px"> <?= $studentOrgData['surname'] . ' ' . $studentOrgData['other_names'] ?></span></td>
                        <td></td>
                        <td><span style="color: #fff;">=======</span></td>

                        <td colspan="2">
                            <span style="font-weight:bold;font-size:12px">REG NO.</span>
                        </td>
                        <td>:
                        </td>

                        <td><span style="font-size:10px"> <?= $studentOrgData['student_number'] ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="8"><span style="font-weight:bold;font-size:12px">PROGRAMME </span></td>
                        <td>:</td>
                        <td><span style="font-size:10px"> <?= $studentOrgData['prog_full_name'] ?></span></td>
                        <td></td>
                        <td></td>
                        <td colspan="2">

                            <span style="font-weight:bold;font-size:12px">ACADEMIC YEAR </span>
                        </td>
                        <td>:</td>
                        <td><span style="font-size:10px"> <?= $data[0]['acad_session_name'] ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="8"><span style="font-weight:bold;font-size:12px">COLLEGE </span></td>
                        <td>:</td>
                        <td><span style="font-size:10px"> <?= strtoupper($info['unit_name']) ?></span></td>
                        <td></td>
                        <td></td>
                        <td colspan="2">

                            <span style="font-weight:bold;font-size:12px">YEAR OF STUDY </span>
                        </td>
                        <td>:</td>
                        <td><span style="font-size:10px"> <?= strtoupper(OrgProgLevel::findOne($data[0]['level_of_study'])->programme_level_name); ?></span></td>
                    </tr>
                </table>


                <!-- <table  >
                    <tr>
                        <td colspan="3">
                            <span class="h1" style="font-weight:bold; font-size:12px">STUDENT NAME:</span>
                            <span style="font-size:10px"> <?= $studentOrgData['surname'] . ' ' . $studentOrgData['other_names'] ?></span>
                        </td>

                        <td colspan="3"><span style="font-weight:bold;font-size:12px; padding-right 50px;">REG NO.:</span><span style="font-size:11px"> <?= $studentOrgData['student_number'] ?></span></td>

                    </tr>
                    <tr>
                        <td colspan="3"><span style="font-weight:bold;font-size:12px">PROGRAMME :</span> <span style="font-size:10px"> <?php //$studentOrgData['prog_full_name']
                                                                                                                                        ?></span></td>
                        <td colspan="3"><span style="font-weight:bold;font-size:12px">ACADEMIC YEAR :</span> <span style="font-size:11px"> <?php // $data[0]['acad_session_name']
                                                                                                                                            ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="3"><span style="font-weight:bold;font-size:12px">COLLEGE: </span> <span style="font-size:10px"> <?php // strtoupper($info['unit_name'])
                                                                                                                                        ?></span></td>
                        <td colspan="3"><span style="font-weight:bold;font-size:12px">YEAR OF STUDY :</span> <span style="font-size:10px"> <?php // strtoupper(OrgProgLevel::findOne($data[0]['level_of_study'])->programme_level_name);
                                                                                                                                            ?></span></td>
                    </tr>
                </table> -->

                <div class="result-area" style="margin-bottom:20px">
                    <table style="border:1px solid black;border-collapse: collapse;   width: 100%;">
                        <tr style="border:1px solid black">
                            <td style="border:1px solid black;text-align: center;">
                                <div style="font-weight:bold; font-size:12px">COURSE <div>CODE</div>
                                </div>
                            </td>
                            <td style="border:1px solid black;text-align: center;">
                                <div style="font-weight:bold;font-size:12px">COURSE TITLE
                                </div>
                            </td>
                            <td style="border:1px solid black;text-align: center;">
                                <div style="font-weight:bold;font-size:12px">ACADEMIC <div>HOURS</div>
                                </div>
                            </td>
                            <td style="border:1px solid black;text-align: center;">
                                <div style="font-weight:bold;font-size:12px">FINAL <div>MARK</div>
                                </div>
                            </td>
                            <td style="border:1px solid black"><span style="font-weight:bold;font-size:12px">GRADE</span></td>
                        </tr>
                        <?php foreach ($data as $row) : ?>

                            <tr>
                                <td style="border:1px solid black;text-align: left;" height="26px"> <span style="font-size:10px">&nbsp;&nbsp;&nbsp;<?= $row['course_code'] ?>&nbsp;&nbsp;&nbsp;</span></td>
                                <td style="border:1px solid black;text-align: left;"> <span style="font-size:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $row['course_name'] ?></span></td>
                                <td style="border:1px solid black;text-align: center;"> <span style="font-size:10px"><?= $row['academic_hours'] ?></span></td>
                                <td style="border:1px solid black; text-align: center">
                                    <span style="font-size:10px">
                                        <?php if ($row['category_name'] == 'PRACTICUM') : ?>
                                            -
                                        <?php else : ?>
                                            <?= $row['final_mark'] ?>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td style="border:1px solid black; text-align: center">
                                    <span style="font-size:10px">
                                        <?php if ($row['category_name'] == 'PRACTICUM') : ?>
                                            PASS
                                        <?php else : ?>
                                            <?= $row['grade'] ?>
                                        <?php endif; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="recommendation-area" style="border-top: 2px solid black; border-bottom: 2px solid black;">
                    <table>
                        <?php
                            $examResult = SmExamResult::find()->select(['result', 'ext_result'])->where(['level_of_study' => $level, 'fk_registration_number' => $studentOrgData['student_number']])->one();
            $distStr = '';
            if(!empty($examResult->ext_result)) {
                if(str_contains($examResult->ext_result, "-")) {
                    $extResultParts = (explode("-", $examResult->ext_result));
                    $distStr = $extResultParts[count($extResultParts) - 1];
                    $extResultFinalStr = str_replace("-", "", $examResult->ext_result);
                    $extResultFinalStr = ' - ' . str_replace($distStr, "", $extResultFinalStr);
                } else {
                    $extResultFinalStr = $examResult->ext_result;
                }
            } else {
                $extResultFinalStr = '';
            }
            ?>
                        <tr>
                            <td colspan="4"><span style="font-weight:bold;font-size:12px">RECOMMENDATION:</span></td>
                            <td><span style="font-size:10px;"><?= $examResult->result ?? '' ?> <?= $extResultFinalStr ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td><span style="font-size:10px;"><?=$distStr?></span></td>
                        </tr>
                    </table>
                </div>

                <div class="grading-system-area" style="margin-bottom:20px; margin-top:10px">
                    <div style="font-style:italic; font-size: 11px;">KEY TO GRADING SYSTEM</div>
                    <table>
                        <?php foreach ($exGrading as $grading) : ?>
                            <tr>
                                <td colspan="3">
                                    <span style="font-size:10px">
                                        <?= $grading->grade ?>
                                    </span>
                                </td>
                                <td>
                                    =
                                </td>
                                <td>
                                    <span style="font-size:10px">
                                        <?= $grading->lower_bound ?>%

                                    </span>
                                    -
                                    <span style="font-size:10px">
                                        <?= $grading->upper_bound ?>%
                                    </span>

                                </td>

                                <td>
                                </td>
                                <td>
                                    <span style="font-size:10px">
                                        <?= $grading->legend ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3">
                                <span style="font-size:10px">
                                    <?= '*' ?>
                                </span>
                            </td>
                            <td>
                                =
                            </td>
                            <td colspan="3">
                                <span style="font-size:10px"> Pass after Supplementary</span>
                            </td>
                        </tr>

                    </table>
                </div>

                <div class="issuer">

                    <table style="width:100%">
                        <tr>
                            <td style="text-align:center">

                                <span>
                                    __________________________ <br>
                                    <div style="font-size:12px; font-weight:bold; text-transform:uppercase; text-align:center;margin-left:10px">Dr S N Mailu</div>
                                    <div style="font-size:12px; ">REGISTRAR (ACADEMIC AFFAIRS)</div>
                                </span>

                            </td>
                            <td></td>

                            <td style="text-align:center" valign="top" align="right">

                                <span style="font-size:12px;">
                                    <div style="text-align:center;margin-left:10px;">
                                        <p style="border-bottom: 1px solid black;">
                                            &nbsp;&nbsp; 17-NOV-2023 &nbsp;&nbsp;
                                            <?php // strtoupper(SmisHelper::formatDate('now', 'd-M-Y')) . PHP_EOL
                                ?>
                                        </p>
                                    </div>
                                    <span style="font-weight: bold">DATE ISSUED</span>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>