<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRegistration\models\AcademicSession $model */

$this->title = Yii::t('app', 'Create Academic Session');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Academic Sessions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-session-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
