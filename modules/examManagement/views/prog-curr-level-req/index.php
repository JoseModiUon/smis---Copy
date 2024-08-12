<?php

use app\modules\examManagement\models\ProgCurrLevelRequirement;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\ProgCurrLevelRequirementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Prog Curr Level Requirements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prog-curr-level-requirement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Prog Curr Level Requirement', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'prog_curr_level_req_id',
            'prog_curriculum_id',
            'prog_study_level',
            'min_courses_taken',
            'pass_type',
            //'min_pass_courses',
            //'gpa_choice',
            //'gpa_courses',
            //'gpa_weight',
            //'pass_result',
            //'pass_recommendation',
            //'fail_type',
            //'fail_result',
            //'fail_recommendation',
            //'incomplete_result',
            //'incomplete_recommendation',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ProgCurrLevelRequirement $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'prog_curr_level_req_id' => $model->prog_curr_level_req_id]);
                }
            ],
        ],
    ]); ?>


</div>
