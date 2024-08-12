<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\generated\Student */

$this->title = $model->STUDENT_ID;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="student-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'STUDENT_ID' => $model->STUDENT_ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'STUDENT_ID' => $model->STUDENT_ID], [
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
            'STUDENT_ID',
            'STUDENT_NUMBER',
            'SURNAME',
            'OTHER_NAMES',
            'GENDER',
            'NATIONALITY',
            'DOB',
            'ID_NO',
            'PASSPORT_NO',
            'BIRTH_CERT_NO',
        ],
    ]) ?>

</div>
