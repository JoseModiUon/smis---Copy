<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChange */

$std=$model->getStudent()->One();

$this->title = $model->current_surname.' '.$model->current_othernames.' - '.$std->student_number;

$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Name Change Request', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sm-name-change-view">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?></h3>


    <?php if ($model->status!='APPROVED'):?>

            <div class="d-flex justify-content-end mb-3 ">
                <?= Html::a('  ACT ON REQUEST', ['sm-name-change-approval/create', 'name_change_id' => $model->name_change_id], ['class' => 'bi bi-pencil-square btn btn-lg btn-primary']) ?>

            </div>

    <?php endif?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'name_change_id',
//            'request_date',

//            'student_id',

        'student.student_number',
            [
                'attribute' => '&nbsp;',
                'value'=>' ',


            ],
            'current_surname',
            'current_othernames',
            [
                'attribute' => '&nbsp;',
                'value'=>'  ',

            ],
            'new_surname',
            'new_othernames',
            'reason',
            //'document_url:url',
            [
                'label' => 'Supporting Document',
                'attribute' => 'document_url',
                'value' => function ($model) {
                    $reg_no=str_replace('/','_',$model['student']['student_number']);
                    return  Html::a(' Download Document', ['/student-records/sm-name-change/download','document_url' => $model->document_url,'file'=>$reg_no.'.pdf'], ['class' => ' bi bi-download btn btn-outline-primary btn-sm']);
                  //  return Yii::$app->urlManager->createUrl(['SmNameChange/download','path'=>$model->document_url,'file'=>'filename.pdf']);

                },
                'format' => 'raw',
            ],
            'status',
            [
                'attribute' => 'request_date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->request_date, 'php:d-M-Y');
                },
            ],
        ],
    ]) ?>

</div>
</div>
</div>
