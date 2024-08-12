<?php

use app\models\OrgSemesterType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\OrgSemesterTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Org Semester Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-semester-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Org Semester Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sem_type_id',
            'sem_type',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, OrgSemesterType $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'sem_type_id' => $model->sem_type_id]);
                }
            ],
        ],
    ]); ?>


</div>
