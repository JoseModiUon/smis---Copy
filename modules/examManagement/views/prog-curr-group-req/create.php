<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrGroupRequirement $model */

$this->title = 'Create Programme Curriculum Group Requirement';
$this->params['breadcrumbs'][] = ['label' => 'Exam Mananagement', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Group Requirements', 'url' => ['#']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="prog-curr-group-requirement-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
