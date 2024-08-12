<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\BankBranches $model */

$this->title = $model->branch_code;
$this->params['breadcrumbs'][] = ['label' => 'Bank Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bank-branches-view">
    <div class="card">
        <div class="card-body">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a('Update', ['update', 'branch_id' => $model->branch_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'branch_id' => $model->branch_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'branch_code',
                    'branch_name',
                    'bank_code',
                    'branch_id',
                ],
            ]) ?>

        </div>
    </div>
</div>