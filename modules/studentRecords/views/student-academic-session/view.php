<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\StudentProgrammeCurriculum $model */

$this->title = $model->student_prog_curriculum_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Programme Curriculums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="student-programme-curriculum-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'student_prog_curriculum_id' => $model->student_prog_curriculum_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'student_prog_curriculum_id' => $model->student_prog_curriculum_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'student_prog_curriculum_id',
            'student_id',
            'registration_number',
            'prog_curriculum_id',
            'student_category_id',
            'adm_refno',
            'status_id',
            'userid',
            'ip_address',
            'user_action',
            'last_update',
        ],
    ]) ?>

</div>
