<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrGroupRequirement $model */

$this->title = 'Update Programme Curriculum  Group Requirement';
$this->params['breadcrumbs'][] = ['label' => 'Exam Mananagement', 'url' => ['/exam-management']];
//$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Group Requirements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Update Programme Curriculum Group Requirements'];
//$this->params['breadcrumbs'][] = ['label' => $model->prog_curr_group_requirement_id, 'url' => ['view', 'prog_curr_group_requirement_id' => $model->prog_curr_group_requirement_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prog-curr-group-requirement-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
