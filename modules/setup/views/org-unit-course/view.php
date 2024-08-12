<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitCourse */

$this->title = $model->org_unit_course_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Unit Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-unit-course-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'org_unit_course_id' => $model->org_unit_course_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'org_unit_course_id' => $model->org_unit_course_id], [
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
            'org_unit_course_id',
            'course_id',
            'org_unit_id',
            'org_teaching_id',
            'start_date',
            'end_date',
            'user_id',
        ],
    ]) ?>

</div>
