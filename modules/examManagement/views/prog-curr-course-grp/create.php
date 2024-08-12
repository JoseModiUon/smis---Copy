<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrCourseGroup $model */

$this->title = 'Create Programme Curriculum Course Group';
$this->params['breadcrumbs'][] = ['label' => 'Exam Mananagement', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Course Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prog-curr-course-group-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
