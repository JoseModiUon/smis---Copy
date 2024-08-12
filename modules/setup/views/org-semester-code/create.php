<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgSemesterCode */

$this->title = 'Create Semester Code';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Semester Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-semester-code-create">

    <div class="card">
        <div class="card-body">

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