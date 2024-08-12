<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\Student;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\helpers\Html;

?>

<div class="">
    <div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'itemLabelPlural' => 'Students',
            'itemLabelSingle' => 'Student',
            'panel' => [
                'after' => '<div class="float-right float-end"></div><div style="padding-top: 5px;"><em>.</em></div><div class="clearfix"></div>',
                'heading' => '<i class="bi bi-tablet-fill"></i>  '.$this->title,
                'type' => 'primary',
                'before' => '<div style="padding-top: 7px;"><em></em></div>',
            ],
            'toolbar' =>  [
                [
                    'content' =>
                        Html::a('<i class="bi bi-arrow-clockwise"></i>', ['/student-records/reports/students-per-sponsor'], [
                            'class' => 'btn btn-outline-secondary',
                            'title'=>'Clear Search',
                            'data-pjax' => 0,
                        ]),
                    'options' => ['class' => 'btn-group mr-2 me-2']
                ],
                '{export}',
//                '{toggleData}',
                'gridOptions'=>['toggleOptions'=>['label'=>'Export Data']],
            ],

            'columns' => [
                ['class' => SerialColumn::class],

                'student_id',
                'service_number',
                'surname',
                'other_names',
                'gender',
                ['attribute' => 'country_code','value'=>function(Student $e){return $e->countryCode->nationality;},],
                'id_no',
                'passport_no',
                ['attribute'=>'sponsor','value'=>function(Student $e){return $e->sponsorList->sponsor_name;},],
            ],
        ]) ?>

    </div>
</div>
