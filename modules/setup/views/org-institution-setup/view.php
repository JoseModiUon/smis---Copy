<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\OrgInstitutionSetup $model */

$this->title = $model->institution_setup_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Institution Setups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-institution-setup-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'institution_setup_id' => $model->institution_setup_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'institution_setup_id' => $model->institution_setup_id], [
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
            'institution_setup_id',
            'unit_id',
            'logo_url:url',
            'favicon_url:url',
            'motto:ntext',
            'email:email',
            'phone',
            'website',
            'postal_address',
        ],
    ]) ?>

</div>
