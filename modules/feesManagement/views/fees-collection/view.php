<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\ProgrammeCurriculum $model */

$this->title = $model->prog_curriculum_id;
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="programme-curriculum-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prog_curriculum_id' => $model->prog_curriculum_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prog_curriculum_id' => $model->prog_curriculum_id], [
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
            'prog_curriculum_id',
            'prog_id',
            'prog_curriculum_desc',
            'duration',
            'pass_mark',
            'annual_semesters',
            'max_units_per_semester',
            'average_type',
            'award_rounding',
            'start_date',
            'end_date',
            'prog_cur_prefix',
            'date_created',
            'grading_system_id',
            'status',
            'approval_date',
            'userid',
            'ip_address',
            'user_action',
            'last_update',
            'billing_type_id',
        ],
    ]) ?>

</div>
