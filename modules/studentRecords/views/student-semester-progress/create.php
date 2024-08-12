<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\StudentProgrammeCurriculum $model */

$this->title = Yii::t('app', 'Create Student Programme Curriculum');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Programme Curriculums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-programme-curriculum-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
