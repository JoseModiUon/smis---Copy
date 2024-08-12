<?php

use app\modules\examManagement\models\ProgCurrGroupRequirement;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\ProgCurrGroupRequirementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Prog Curr Group Requirements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prog-curr-group-requirement-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('Create Prog Curr Group Requirement', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'prog_curr_group_requirement_id',
            'prog_curr_level_req_id',
            'prog_curr_course_group_id',
            'min_group_courses',
            'group_pass_type',
            //'min_group_pass',
            //'gpa_pass_type',
            //'gpa_courses',
            //'extra_courses_processing',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ProgCurrGroupRequirement $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'prog_curr_group_requirement_id' => $model->prog_curr_group_requirement_id]);
                }
            ],
        ],
    ]); ?>


</div>
