<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */
$student = $model->getStudent()->one();
$this->title = 'Withdrawal Request ';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sm-withdrawal-request-view">

    <div class="card" >
        <div class="card-body">

            <div class="d-flex justify-content-end">
                <?= Html::a('Act on Request', [
                    'sm-withdrawal-approval/create', 'withdrawal_request_id' => $model->withdrawal_request_id
                    ], ['class' => 'bi bi-pencil-square btn btn-primary']) 
                ?>

            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title .  $student->getFullNames() . ' '.$student->student_number) ?></h3>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // 'withdrawal_request_id',
                    [
                        'attribute' => 'withdrawal_type_id',
                        'label' => 'Withdrawal Type',
                        'value' => function($model) {
                            return $model->getSmWithdrawalType()->one()->withdrawal_type_name;
                        }
                    ],
                    
                    [
                        'attribute' => 'request_date',
                        'label' => 'Request Date',
                        'value' => function($model) {
                            return strtoupper(Yii::$app->formatter->asDate($model->request_date, 'php:d-M-yy'));
                        }
                    ],
                    'reason',
                    'approval_status',
                    [
                        'label' => 'Supporting Document',
                        'attribute' => 'supporting_doc_url',
                        'value' => function ($model) {
                            $reg_no=str_replace('/','_',$model['student']['student_number']);
                            return Html::a(' Download Document ', ['/student-records/sm-withdrawal-request/download','document_url' => $model->supporting_doc_url,'file'=>$reg_no.'.pdf'], ['class' => 'bi bi-download btn btn-outline-primary btn-sm']);
        
                        },
                        'format' => 'raw',
                    ],
                    // 'student_id',
                ],
            ]) ?>
        </div>
    </div>
</div>
