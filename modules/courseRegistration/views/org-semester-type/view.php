<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\OrgSemesterType $model */

$this->title = $model->sem_type_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Semester Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<<<<<<< HEAD
<<<<<<< HEAD
<div class="org-semester-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
<div class="org-semester-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
        <?= Html::a('Update', ['update', 'sem_type_id' => $model->sem_type_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'sem_type_id' => $model->sem_type_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
<<<<<<< HEAD
<<<<<<< HEAD
    </p>

=======
    </p>

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    </p>

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sem_type_id',
            'sem_type',
        ],
    ]) ?>
<<<<<<< HEAD
<<<<<<< HEAD

</div>
=======

</div>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======

</div>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
