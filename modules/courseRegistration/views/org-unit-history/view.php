<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitHistory */

$this->title = $model->org_unit_history_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Unit Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<<<<<<< HEAD
<<<<<<< HEAD
<div class="org-unit-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
<div class="org-unit-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
        <?= Html::a('Update', ['update', 'org_unit_history_id' => $model->org_unit_history_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'org_unit_history_id' => $model->org_unit_history_id], [
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
            'org_unit_history_id',
            'org_unit_id',
            'org_type_id',
            'parent_id',
            'successor_id',
            'unit_head_id',
            'unit_head_user_id',
            'start_date',
            'end_date',
            'user_id',
            'date_created',
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
