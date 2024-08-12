<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgKuccpsProgMap */

$this->title = 'Update Kuccps Program Map';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Update Kuccps Program Map', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-kuccps-prog-map-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            
        </div>
    </div>

</div>
