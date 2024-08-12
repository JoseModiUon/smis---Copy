<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\BillingType $model */

$this->title = Yii::t('app', 'Create Billing Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programme Curriculum'), 'url' => ['programme-curriculum/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-type-create">

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