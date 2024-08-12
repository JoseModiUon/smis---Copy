<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChange */

$this->title = 'Create Sm Name Change';
$this->params['breadcrumbs'][] = ['label' => 'Sm Name Changes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-name-change-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
