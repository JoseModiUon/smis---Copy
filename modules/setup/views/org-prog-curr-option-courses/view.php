<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrOptionCourses */

$this->title = $model->option_course_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Prog Curr Option Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-prog-curr-option-courses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'option_course_id' => $model->option_course_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'option_course_id' => $model->option_course_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'option_course_id',
            'option_id',
            'course_id',
            'prog_cur_id',
            'course_type',
            'degree_code',
        ],
    ]) ?>

</div>
