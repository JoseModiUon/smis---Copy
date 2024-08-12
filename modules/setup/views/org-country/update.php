<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCountry */

$this->title = 'Update Country: ' . $model->country_name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->country_code, 'url' => ['', 'country_code' => $model->country_code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-country-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="text-center"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
