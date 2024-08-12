<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrLevelRequirement $model */

$this->title = 'Update Programme Curriculum Level Requirement';

$this->params['breadcrumbs'][] = ['label' => 'Exam Mananagement', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Programme requierements'];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="prog-curr-level-requirement-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
