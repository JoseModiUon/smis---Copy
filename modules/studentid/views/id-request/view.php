<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\studentid\models\StudentId */

$this->title = $model->student_id_serial_no;
$this->params['breadcrumbs'][] = ['label' => 'Student Id', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-id-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Student Id'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a('Update', ['update', 'id' => $model->student_id_serial_no], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->student_id_serial_no], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'student_id_serial_no',
        [
            'attribute' => 'studentProgCurr.student_prog_curriculum_id',
            'label' => 'Student Prog Curr',
        ],
        'issuance_date',
        'valid_from',
        'valid_to',
        'barcode',
        'id_status',
        'request_id',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>SmStudentProgrammeCurriculum<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnSmStudentProgrammeCurriculum = [
        'student_prog_curriculum_id',
        'student_id',
        'registration_number',
        'prog_curriculum_id',
        'student_category_id',
        'adm_refno',
        'status_id',
    ];
    echo DetailView::widget([
        'model' => $model->studentProgCurr,
        'attributes' => $gridColumnSmStudentProgrammeCurriculum    ]);
    ?>
    
    <div class="row">
<?php
if($providerSmStudentIdDetails->totalCount){
    $gridColumnSmStudentIdDetails = [
        ['class' => 'yii\grid\SerialColumn'],
            'stud_id_det_id',
                        'student_id_status',
            'remarks',
            'status_date',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerSmStudentIdDetails,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-sm-student-id-details']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Sm Student Id Details'),
        ],
        'export' => false,
        'columns' => $gridColumnSmStudentIdDetails
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerSmStudentIdStatus->totalCount){
    $gridColumnSmStudentIdStatus = [
        ['class' => 'yii\grid\SerialColumn'],
            'id_status_no',
            'status_name',
                ];
    echo Gridview::widget([
        'dataProvider' => $providerSmStudentIdStatus,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-sm-student-id-status']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Sm Student Id Status'),
        ],
        'export' => false,
        'columns' => $gridColumnSmStudentIdStatus
    ]);
}
?>

    </div>
</div>
