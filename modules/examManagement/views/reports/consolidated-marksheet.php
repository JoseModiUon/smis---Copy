<?php

use app\models\CrProgCurrTimetable;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgRooms;
use app\models\SmExamResult;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\dialog\Dialog;

/** @var yii\web\View $this */
/** @var app\models\search\CrProgCurrTimetableSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

echo Dialog::widget([
    'libName' => 'krajeeDialogCust',
    'options' => ['draggable' => true, 'closable' => true], // custom options
]);

$this->title = 'Consolidated  Marksheet';

$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/exam-management']];
// $this->params['breadcrumbs'][] = ['label' => 'Consolidated Marksheet', 'url' => ['/exam-management/reports/view-consolidated-marksheet']];
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;

function findRecommendation($stno, $level): string
{
    $return = SmExamResult::find()->where(['fk_registration_number' => $stno, 'level_of_study' => $level])->one();

    return $return['result'] . ' : ' . $return['ext_result'];
}


$prog = OrgProgrammeCurriculum::find()->select('org_programmes.prog_code')
    ->innerJoinWith('prog')
    ->where(['prog_curriculum_id' => Yii::$app->request->get('ConsolidatedMarksheetSearch')['prog_curriculum_id'] ?? 0])->asArray()->one();


?>
<div class="cr-prog-curr-timetable-index">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
            <br>

            <div class="d-flex justify-content-end mt-3"></div>
            <div class="mt-3 mb-3">
                <?= $this->render('_consolidated_marksheet_search', ['model' => $searchModel]) ?>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <?php if (!empty($data)) : ?>
                    <button class="btn btn-success" id="save-marksheet-btn"><i class="fas fa-download"></i> Export</button>
                <?php endif; ?>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <?php foreach ($data as $key => $row) : ?>

                        <table class="table">
                            <tr>
                                <td><b><?= $key + 1 . '. ' . $row['student_number'] . ' ' . $row['name'] ?></b></td>
                                <td colspan="7"><b><?= findRecommendation($row['student_number'], $row['level_of_study']) ?></b></td>
                            </tr>
                        </table>
                        <table class="table">
                            <tr>
                                <?php foreach ($row as $key => $value) : ?>
                                    <?php if (str_starts_with($key, 'course_code')) : ?>

                                        <td style="border:none;"><?= $value ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <?php foreach ($row as $key => $value) : ?>

                                    <?php if (str_starts_with($key, 'final_mark')) : ?>
                                        <td>
                                            <?= $value ?> |
                                        <?php elseif (str_starts_with($key, 'grade')) : ?>
                                            <?= $value ?>
                                        </td>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                                <td></td>
                                <td><?= $row['total_subjects'] ?></td>
                                <td><?= $row['total_mark'] ?></td>
                                <td><?= number_format((float)$row['average_mark'], 2, '.', ''); ?></td>
                                <td><?php
                                    // $return = SmExamResult::find()->where(['fk_registration_number' => $row['student_number'], 'level_of_study' => $row['level_of_study']])->one()->result;

                                    ?></td>
                            </tr>
                        </table>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>


<?php
$urlSaveMarksheet = Url::to(['/exam-management/reports/save-consolidated-marksheet']);
$encodedData = json_encode($data);
$requestData = json_encode(array_merge(Yii::$app->request->get('ConsolidatedMarksheetSearch') ?? [], $prog ?? []));

$marksheetJs = <<< JS
let urlSaveMarksheet = '$urlSaveMarksheet';
const data = $encodedData
const requestData = $requestData



function fetchMarksheet() {
    console.log(data)
    $.ajax({
        url: urlSaveMarksheet,
        type: 'POST',
        data: {data:data, requestData:requestData},
        dataType: 'json',  
        success: function(data) {
            let url = URL.createObjectURL(dataURItoBlob(data.output));
            let link = document.createElement('a');
            link.href = url;
            link.download = requestData.prog_code + '.pdf';
            link.click();
        },
        error: function(err) {
            // $('#app-is-loading-modal').modal('hide');
            krajeeDialog.alert('could not generate file.');
        }
    })
}

function dataURItoBlob(dataURI) {
    const byteString = window.atob(dataURI);
    const arrayBuffer = new ArrayBuffer(byteString.length);
    const int8Array = new Uint8Array(arrayBuffer);
    for (let i = 0; i < byteString.length; i++) {
        int8Array[i] = byteString.charCodeAt(i);
    }
    const blob = new Blob([int8Array], { type: 'application/pdf'});
    return blob;
}
document.getElementById('save-marksheet-btn').addEventListener('click', e => {
    fetchMarksheet()
})

JS;
$this->registerJs($marksheetJs, yii\web\View::POS_READY);
