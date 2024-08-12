<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgStudyCentre */

$this->title = $model->study_centre_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Study Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-study-centre-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'study_centre_id' => $model->study_centre_id], ['class' => 'btn btn-primary']) ?>
<!--        <?//= Html::a('Delete', ['delete', 'study_centre_id' => $model->study_centre_id], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => 'Are you sure you want to delete this item?',
//                'method' => 'post',
//            ],
//        ]) ?>-->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'study_centre_id',
            'study_centre_name',
            'status',
        ],
    ]) ?>

</div>
