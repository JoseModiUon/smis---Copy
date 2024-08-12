<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
// use yii\grid\GridView;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SemesterCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Semester Codes';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="semester-code-index">
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-end">
                <?=
                Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Semester Code',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary align-right']
                ) ?>
            </div>


            <div class="d-flex flex-column">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?></h3>
            </div>


            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    'semester_code',
                    [
                        'label' => 'Semester Name',
                        'attribute' => 'semster_name',
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Update',
                                    ['/setup/org-semester-code/update', 'semester_code' => $model->semester_code],
                                    ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']
                                );
                            },
                        ]
                    ],
                ]
            ]); ?>
        </div>
    </div>
</div>