<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgSemesterCode */

$this->title = $model->semester_code;
$this->params['breadcrumbs'][] = ['label' => 'Org Semester Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-semester-code-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'semester_code' => $model->semester_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'semester_code' => $model->semester_code], [
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
            'semester_code',
            'semster_name',
        ],
    ]) ?>

</div>
