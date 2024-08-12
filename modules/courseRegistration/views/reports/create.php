<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrProgCurrTimetable $model */

$this->title = 'Create Course Programme Curriculum Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = $this->title;
?>
<<<<<<< HEAD
<<<<<<< HEAD
<div class="cr-prog-curr-timetable-create">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
<div class="cr-prog-curr-timetable-create">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
            <?= $this->render('_form', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'params' => Yii::$app->request->get() ?? []
            ]) ?>
<<<<<<< HEAD
<<<<<<< HEAD

        </div>
    </div>
=======

        </div>
    </div>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======

        </div>
    </div>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
</div>