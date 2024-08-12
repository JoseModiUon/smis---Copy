<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRegistration\models\AcademicSession $model */

$this->title = Yii::t('app', 'Update Academic Session: {name}', [
    'name' => $model->acad_session_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Academic Sessions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->acad_session_id, 'url' => ['view', 'acad_session_id' => $model->acad_session_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="academic-session-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
