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


$additionalParams = Yii::$app->request->get();


?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-12">
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
                        'attribute' => 'grade',
                        'label' => 'Grade',
                        'value' => function ($model) {
                            return $model['grade'];
                        },

                    ],




                ],
            ]); ?>


        </div>
    </div>
</div>


<?php
$urlSaveTranscript = Url::to(['/exam-management/reports/save-transcript']);
$data = json_encode($additionalParams);

$transcriptJs = <<< JS
let urlSaveTranscript = '$urlSaveTranscript';
const data = $data

console.log(data)

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
            link.download = 'Transcript.pdf';
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

JS;
$this->registerJs($transcriptJs, yii\web\View::POS_READY);
