<?php

use app\models\AdmittedStudent;
use app\models\Intakes;
use app\models\IntakeSource;
use app\models\OrgProgrammes;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\AdmittedStudentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Admitted Students';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Reports',];
$this->params['breadcrumbs'][] = $this->title;

$statuses= ArrayHelper::map(AdmittedStudent::find()->select('admission_status')
    ->distinct()->asArray()->all(),'admission_status','admission_status');
$sources= ArrayHelper::map(IntakeSource::find()->asArray()->all(),'source_id','source');
$intakes= ArrayHelper::map(Intakes::find()->asArray()->all(),'intake_code','intake_name');
$programmes= ArrayHelper::map(OrgProgrammes::find()->asArray()->all(),'prog_code',fn($d)=>$d['prog_code'].' - '.$d['prog_short_name']);
$yrList = range(date('Y'),2010);
$yrs = array_combine($yrList,$yrList);
?>
<div class="admitted-student-index" style="overflow: hidden;">

    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('<i class="bi bi-arrow-clockwise"></i> Clear Filters', ['admitted-list'], ['class' => 'mt-2 me-1 btn btn-primary float-end']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'containerOptions' => ['style'=>'overflow-y:auto; width:100%;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'adm_refno',
            'kcse_index_no',
            ['attribute' => 'kcse_year','filter' => $yrs],
            'surname',
            'other_names',
            ['attribute' => 'primary_phone_no','value' => fn(AdmittedStudent $d)=>strlen($d->primary_phone_no)>11?"+$d->primary_phone_no":$d->alternative_phone_no,],
            ['attribute' => 'alternative_phone_no','value' => fn(AdmittedStudent $d)=>strlen($d->alternative_phone_no)>11?"+$d->alternative_phone_no":$d->alternative_phone_no,],
            'primary_email:email',
            'alternative_email:email',
            'post_code',
            'post_address',
            'town',
            'kuccps_prog_code',
            ['attribute' => 'uon_prog_code','filter' => $programmes],
            //'national_id',
            //'birth_cert_no',
            ['attribute' => 'source_id','value'=>fn(AdmittedStudent $d)=>$d->source->source,'filter' => $sources],
            //'passport_no',
            ['attribute' => 'admission_status','filter' => $statuses],
            //'application_refno',
            ['attribute' => 'intake_code','value' => fn(AdmittedStudent $d)=>$d->intakeCode->intake_name,'filter' => $intakes],
            //'student_category_id',
//            [
//                'class' => ActionColumn::class,
//                'urlCreator' => function ($action, AdmittedStudent $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'adm_refno' => $model->adm_refno]);
//                 }
//            ],
        ],
    ]); ?>


</div>
