<?php

use app\models\ExMarksheet;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\ExMarksheetSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Ex Marksheets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ex-marksheet-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ex Marksheet', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'marksheet_id',
            'student_course_reg_id',
            'course_work_mark',
            'exam_mark',
            'final_mark',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ExMarksheet $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'marksheet_id' => $model->marksheet_id]);
                 }
            ],
        ],
    ]); ?>


</div>
