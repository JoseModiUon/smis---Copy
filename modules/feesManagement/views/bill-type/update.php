<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\BillingType $model */

$this->title = Yii::t('app', '{name}', [
    'name' => $model->billing_type_desc,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Billing Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="billing-type-update">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
                    </div>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>