<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgAcademicSessionSemester */

$this->title = $model->acad_session_semester_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Academic Session Semesters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-academic-session-semester-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'acad_session_semester_id' => $model->acad_session_semester_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'acad_session_semester_id' => $model->acad_session_semester_id], [
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
            'acad_session_semester_id',
            'acad_session_id',
            'semester_code',
            'acad_session_semester_desc',
        ],
    ]) ?>

</div>
