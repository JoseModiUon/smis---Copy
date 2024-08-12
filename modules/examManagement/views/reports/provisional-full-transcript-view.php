<?php

use app\models\OrgProgLevel;
use app\models\Student;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Provisional Transcript';
$this->params['breadcrumbs'][] = ['label' => 'Provisional Transcript', 'url' => ['provisional-transcript']];
$this->params['breadcrumbs'][] = $this->title;
$reg_no = Yii::$app->request->queryParams['reg_no'];
$student = Student::find()->select(['student_id', 'concat(student_number,\' - \',concat(surname,\' \',other_names)) AS desc'])
    ->where(['student_number' => $reg_no])
    ->asArray()->one();

$additionalParams = Yii::$app->request->get();


?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-12">
                    <p><b><?= $student['desc'] ?></b> </p>


                </div>
            </div>
            <hr>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                // 'export' => false,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'exportContainer' => ['class' => 'btn-group mr-2 me-2'],
                'export' => false,
                'toolbar' => [
                    '{toggleData}',
                    [
                        'content' =>
                        Html::button(
                            '<i class="fas fa-check"></i> Save Transcript',
                            [
                                'title' => 'Save Transcript',
                                'id' => 'save-transcript-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]
                        ) .
                            Html::button(
                                '<i class="fas fa-check"></i> Save Transcript Logo',
                                [
                                    'title' => 'Save Transcript Logo',
                                    'id' => 'save-transcript-logo-btn',
                                    'class' => 'btn btn-success btn-spacer btn-sm',
                                ]
                            ),

                        'options' => ['class' => 'btn-group mr-2']
                    ],
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],


                    [
                        'attribute' => 'Summary Header',
                        'width' => '310px',
                        'value' => function ($model) {
                            if (!empty($model)) {
                                $level = OrgProgLevel::findOne($model['programme_level_id']);
                                return $level['programme_level_name'];
                            }
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        // 'filter' => ArrayHelper::map(Suppliers::find()->orderBy('company_name')->asArray()->all(), 'id', 'company_name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        // 'filterInputOptions' => ['placeholder' => 'Any supplier'],
                        'group' => true,  // enable grouping,
                        'groupedRow' => true,                    // move grouped column to a single grouped row
                        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                        'format' => 'raw'
                    ],

                    [
                        'attribute' => 'course',
                        'label' => 'COURSE CODE',
                        'value' => function ($model) {
                            return $model['course_code'];
                        },

                    ],
                    [
                        'attribute' => 'course',
                        'label' => 'COURSE TITLE',
                        'value' => function ($model) {
                            return $model['course_name'];
                        },

                    ],
                    [
                        'attribute' => 'course',
                        'label' => 'ACADEMIC HOURS',
                        'value' => function ($model) {
                            return $model['academic_hours'];
                        },

                    ],
                    [
                        'attribute' => 'course',
                        'label' => 'FINAL MARK',
                        'value' => function ($model) {
                            // dd($model);
                            if ($model['category_name'] == 'PRACTICUM') {
                                return '-';
                            }
                            return $model['final_mark'];
                        },

                    ],
                    [
                        'attribute' => 'grade',
                        'label' => 'GRADE',
                        'value' => function ($model) {
                            if ($model['category_name'] == 'PRACTICUM') {
                                return 'PASS';
                            }
                            return $model['grade'];
                        },

                    ],




                ],
            ]); ?>


        </div>
    </div>
</div>


<?php
$urlSaveTranscript = Url::to(['/exam-management/reports/save-transcript-full']);
$urlSaveTranscriptLogo = Url::to(['/exam-management/reports/save-transcript-logo-full']);
$data = json_encode($additionalParams);

$transcriptJs = <<< JS
let urlSaveTranscript = '$urlSaveTranscript';
let urlSaveTranscriptLogo = '$urlSaveTranscriptLogo';
const data = $data

console.log(data)

filename = data.reg_no.replace("/", "_")

function fetchTranscript() {
    $.ajax({
        url: urlSaveTranscript,
        type: 'POST',
        data: data,
        dataType: 'json',  
        success: function(data) {
            let url = URL.createObjectURL(dataURItoBlob(data.output));
            let link = document.createElement('a');
            link.href = url;
            link.download = filename + '.pdf';
            link.click();
        },
        error: function(err) {
            // $('#app-is-loading-modal').modal('hide');
            krajeeDialog.alert('could not generate file.');
        }
    })
}
function fetchTranscriptLogo() {
    $.ajax({
        url: urlSaveTranscriptLogo,
        type: 'POST',
        data: data,
        dataType: 'json',  
        success: function(data) {
            let url = URL.createObjectURL(dataURItoBlob(data.output));
            let link = document.createElement('a');
            link.href = url;
            link.download = filename + '.pdf';
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
document.getElementById('save-transcript-btn').addEventListener('click', e => {
    fetchTranscript()
})

document.getElementById('save-transcript-logo-btn').addEventListener('click', e => {
    fetchTranscriptLogo()
})

JS;
$this->registerJs($transcriptJs, yii\web\View::POS_READY);
