<?php

use app\models\SmStudentCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\StudentCategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Student Categories';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sm-student-category-index">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Student Category',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                ?>
            </div>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'std_category_name',
                        'label' => 'category_name'
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
                                    ' Update',
                                    [
                                        '/student-records/student-category/update',
                                        'std_category_id' => $model->std_category_id
                                    ],
                                    ['class' => ' bi btn btn-outline-primary bi-pencil-square']
                                );
                            },
                        ]
                    ],
                ],
            ]); ?>

        </div>
    </div>

</div>