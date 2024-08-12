<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgSemesterCode */

$this->title = 'Update Org Semester Code: ' . $model->semester_code;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Org Semester Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-semester-code-update">


    <div class="card">

        <div class="d-flex flex-column">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?></h3>
        </div>


        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>

</div>