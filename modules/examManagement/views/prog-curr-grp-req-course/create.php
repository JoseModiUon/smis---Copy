<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrGroupReqCourse $model */

$this->title = 'Create Prog Curr Group Req Course';
$this->params['breadcrumbs'][] = ['label' => 'Prog Curr Group Req Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prog-curr-group-req-course-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
