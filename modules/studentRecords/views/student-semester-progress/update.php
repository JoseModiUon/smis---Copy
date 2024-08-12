<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\StudentProgrammeCurriculum $model */

$this->title = Yii::t('app', 'Update Student Programme Curriculum: {name}', [
    'name' => $model->student_prog_curriculum_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Programme Curriculums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->student_prog_curriculum_id, 'url' => ['view', 'student_prog_curriculum_id' => $model->student_prog_curriculum_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="student-programme-curriculum-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
