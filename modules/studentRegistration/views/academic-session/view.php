<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\studentRegistration\models\AcademicSession $model */

$this->title = $model->acad_session_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Academic Sessions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="academic-session-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'acad_session_id' => $model->acad_session_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'acad_session_id' => $model->acad_session_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'acad_session_id',
            'acad_session_name',
            'acad_session_desc',
            'start_date',
            'end_date',
            'userid',
            'ip_address',
            'user_action',
            'last_update',
        ],
    ]) ?>

</div>
