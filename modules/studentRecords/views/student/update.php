<<<<<<< HEAD
<<<<<<< HEAD
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\generated\Student */

$this->title = 'Update Student: ' . $model->STUDENT_ID . ' '.$model->SURNAME;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "#{$model->STUDENT_ID} - {$model->SURNAME}", 'url' => ['view', 'STUDENT_ID' => $model->STUDENT_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\generated\Student */

$this->title = 'Update Student: ' . $model->STUDENT_ID . ' '.$model->SURNAME;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "#{$model->STUDENT_ID} - {$model->SURNAME}", 'url' => ['view', 'STUDENT_ID' => $model->STUDENT_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
<div class="student-update">

    <h3><?= Html::encode($this->title) ?></h3>

<<<<<<< HEAD
<<<<<<< HEAD
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
=======
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

</div>
