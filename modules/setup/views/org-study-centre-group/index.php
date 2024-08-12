<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use app\models\OrgStudyCentreGroup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgStudyCentreGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Study Centre Groups';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-study-centre-group-index">
    <div class="card" >
        <div class="card-body">
            <div class="d-flex justify-content-end">

                    <?= Html::a('<i class="bi bi-plus-lg"></i> Create Study Centre Group', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>



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

            //'study_centre_group_id',
            //'study_centre_id',
            [
                'attribute' => 'studyCentre',
                'label' => 'Study Centre Name',
                'value' => 'studyCentre.study_centre_name',
            ],
           // 'studyCentre.study_centre_name',
//            'study_group_id',
            [
                'attribute' => 'studyGroup',
                'label' => 'Study Group Name',
                'value' => 'studyGroup.study_group_name',
            ],
            // 'studyGroup.study_group_name',
            'status',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgStudyCentreGroup $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'study_centre_group_id' => $model->study_centre_group_id]);
//                 }
//            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/setup/org-study-centre-group/update','study_centre_group_id' => $model->study_centre_group_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
        ],
    ]); ?>


</div>
</div>
</div>
