<?php

use app\helpers\SmisHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\FeeItems $model */

$this->title = $title;

echo SmisHelper::createBreadcrumb([
    'Fees Management' => Url::to(['/fees-management']),
    'Fee Items' => Url::to(['/fees-management/fee-items']),
    $title => null
]);
?>

<div class="section">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center text-bold"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
            </div>
        </div>


    </div>
</div>