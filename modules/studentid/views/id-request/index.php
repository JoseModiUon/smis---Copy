<?php

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\studentid\models\search\StudentIdSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Student Id';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="student-id-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $gridColumn = [
        ['class' => 'kartik\grid\SerialColumn'],
        'student_id_serial_no',
        [
            'attribute' => 'student_prog_curr_id',
            'value' => function ($model) {
                /* @var $model app\modules\studentid\models\StudentId */
                return $model->studentProgCurr->progCurriculum->prog_curriculum_desc;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\models\SmStudentProgrammeCurriculum::find()->asArray()->all(), 'student_prog_curriculum_id', 'student_prog_curriculum_id'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Sm student programme curriculum', 'id' => 'grid-student-id-search-student_prog_curr_id']
        ],
        'issuance_date:date',
        'valid_from:date',
        'valid_to:date',
        'barcode',
        'id_status',
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update}',
            'width' => '15%',
//            'visible'=>false,
            'buttons' => [
                'update' => function ($url, $model) {
                    /* @var $model app\modules\studentid\models\StudentId */
                    return Html::a('<i class="fa fa-user"></i> Update ID status', [
                        'update', 'id' => $model->student_id_serial_no
                    ], ['title' => 'Update id', 'class' => 'btn btn-sm btn-outline-success']);
                },
            ],
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-id']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        'export' => false,
    ]); ?>

</div>
