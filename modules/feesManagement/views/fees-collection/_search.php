<?php

use app\models\OrgStudyGroup;
use app\modules\feesManagement\models\ProgrammeCurriculum;
use app\modules\feesManagement\models\StudentGroup;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\search\FeesCollectionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="programme-curriculum-search">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">

                    <?php $form = ActiveForm::begin([
                        'action' => ['index'],
                        'method' => 'get',
                        'enableAjaxValidation' => true,
                    ]); ?>
                    <p class="text-bold">
                        <?php
                        $total = Yii::$app->params['total'];
                        echo 'Total: ' . $total;
                        ?>
                    </p>
                    <div class="d-flex flex-column">

                        <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $programmeCurriculums = ProgrammeCurriculum::find()->joinWith('orgProgrammes')->all();
                            $data = [];
                            foreach ($programmeCurriculums as $programmeCurriculum) {
                                $data[$programmeCurriculum->prog_curriculum_id] = $programmeCurriculum->orgProgrammes->prog_code . ' - ' . $programmeCurriculum->prog_curriculum_desc;
                            }
                            echo $form->field($model, 'prog_curriculum_id')->widget(Select2::class, [
                                'data' => $data,
                                'options' => ['placeholder' => 'Select a state ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Degree Programme');
                            ?>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <?php

                            $prog = ArrayHelper::map(
                                ProgrammeCurriculum::find()
                                    ->joinWith(['studentProgrammeCurriculum.academicProgress.academicSession'])
                                    ->all(),
                                'studentProgrammeCurriculum.academicProgress.academicSession.acad_session_name',
                                'studentProgrammeCurriculum.academicProgress.academicSession.acad_session_name'
                            );

                            // Render the Select2 widget
                            echo $form->field($model, 'acad_session_name')->widget(Select2::class, [
                                'data' => $prog,
                                'options' => ['placeholder' => 'Select an Academic Session ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label('Academic Session');
                            ?>
                        </div>
                        <div class="col-md-6">

                            <?php

                            $prog = ArrayHelper::map(
                                OrgStudyGroup::find()
                                    ->all(),
                                'study_group_id',
                                'study_group_name'
                            );
                            // Render the Select2 widget
                            echo $form->field($model, 'study_group_name')->widget(Select2::class, [
                                'data' => $prog,
                                'options' => ['placeholder' => 'Select an Ref Number ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label('Student Group');
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo $form->field($model, 'start_date')->widget(DatePicker::class, [
                                'options' => ['placeholder' => 'Enter Start Date ...'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd',
                                ]
                            ]);
                            ?>
                        </div>

                        <div class="col-md-6">

                            <?php
                            echo $form->field($model, 'end_date')->widget(DatePicker::class, [
                                'options' => ['placeholder' => 'Enter End Date ...'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd',
                                ]
                            ]);
                            ?>
                        </div>
                    </div>

                    <?php $form->field($model, 'prog_id') ?>

                    <?php $form->field($model, 'prog_curriculum_desc') ?>

                    <?php $form->field($model, 'duration') ?>

                    <?php $form->field($model, 'pass_mark') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>