<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\OrgProgCurrCourse $model */

$this->title = 'Create Org Prog Curr Course';
$this->params['breadcrumbs'][] = ['label' => 'Org Prog Curr Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-course-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
