<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use app\models\OrgStudyCentre;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgStudyCentreCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Study Centres';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-study-centre-index">
    <div class="card" >
        <div class="card-body">
    <div class="d-flex justify-content-end">
        <?= Html::a('<i class="bi bi-plus-lg"></i> Create Study Centre', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>
    <h3><?= Html::encode($this->title) ?></h3>



<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>
=======
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'study_centre_id',
            'study_centre_name',
            'status',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/setup/org-study-centre/update','study_centre_id' => $model->study_centre_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],

        ],
    ]); ?>


</div>
</div>
</div>
