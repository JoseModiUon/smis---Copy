<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\ProgrammeCurriculum $model */

$this->title = 'Create Programme Curriculum';
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programme-curriculum-create">
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