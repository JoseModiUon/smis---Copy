<?php

use app\modules\feesManagement\models\Banks;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\feesManagement\models\search\BankingSlipsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fee Statement Report';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="banking-slips-index">
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h5 class="text-center text-body-secondary fw-bold">Select the student to generate statement</h5>
                    <br>
                    <?php echo  $this->render('_search', ['model' => $searchModel]) ?>
                    <hr>
                </div>
            </div>



            <?= GridView::widget([
                'id' => 'banking-slips-table',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'striped' => true,
                'hover' => true,
                'condensed' => true,
                'responsive' => true,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'toolbar' => [
                    '{toggleData}',
                ],
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                   
                    [
                        'attribute' => 'reg_number',
                        'label' => 'Registration Number',
                        'value' => function ($model) {
                            return $model['student_number'];
                        }
                    ],
                    [
                        'attribute' => 'other_names',
                        'label' => 'Name',
                        'value' => function ($model) {
                            return $model['other_names'];
                        }
                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' View Statement ',
                                    [
                                        '/fees-management/reports/view-statement',
                                        'reg_number' => $model['student_number'],
                                    ],
                                    ['class' => 'bi bi-eye-fill btn-link text-primary ']
                                );
                            },
                        ]
                    ],


                ],

            ]) ?>
        </div>
    </div>
</div>