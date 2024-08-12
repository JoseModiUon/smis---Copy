<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrgCourses;
use app\models\OrgProgrammeCurriculum;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrCourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="form-group">
            <h6 class="card-title text-danger">Select Programme Curriculum and click on Search</h6>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgProgrammeCurriculum::find()
                ->select(['prog_curriculum_id', 'concat(prog_code,\' - \',prog_curriculum_desc) AS desc'])
                ->joinWith('prog')
                ->asArray()->all();
            $data = ArrayHelper::map($types, 'prog_curriculum_id', 'desc');
            echo $form
                ->field($model, 'prog_curriculum_id')
                ->label('Programme Curriculum', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Programme Curriculum ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <?= Html::a('  Reset Page', ['/courseRegistration/org-prog-curr-course/index'], ['class' => 'bi bi-arrow-clockwise btn btn-outline-secondary']); ?>
                <?= Html::submitButton('  Search', ['class' => 'bi bi-search btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php if (null !== Yii::$app->request->get('OrgProgCurrCourseSearch')) : ?>
        <div class="row mb-2">
            <div class="form-group">
                <div class="d-flex justify-content-between">
                    <?= Html::a('  Add Courses', ['/courseRegistration/org-prog-curr-course/add-courses', 'prog_curriculum_id' => Yii::$app->request->get('OrgProgCurrCourseSearch')['prog_curriculum_id']], ['class' => 'bi bi-plus btn btn-outline-success']); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>