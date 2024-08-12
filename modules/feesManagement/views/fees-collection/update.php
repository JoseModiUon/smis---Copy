<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\ProgrammeCurriculum $model */

$this->title = $model->orgProgrammes->prog_code .' - '.$model->prog_curriculum_desc;
// $this->title = 'UPDATE PROGRAMME BILLING TYPE';
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculums', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="programme-curriculum-update">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
                    </div>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>