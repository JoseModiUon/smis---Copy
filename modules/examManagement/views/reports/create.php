<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrProgCurrTimetable $model */

$this->title = 'Create Course Programme Curriculum Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cr-prog-curr-timetable-create">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'params' => Yii::$app->request->get() ?? []
            ]) ?>

        </div>
    </div>
</div>