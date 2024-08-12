<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCountry */

$this->title = $model->country_code;
$this->params['breadcrumbs'][] = ['label' => 'Org Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-country-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'country_code' => $model->country_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'country_code' => $model->country_code], [
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
            'country_code',
            'country_name',
            'continent',
            'region',
            'code2',
            'nationality',
        ],
    ]) ?>

</div>
