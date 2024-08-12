<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgProgCurrSemester;
use app\models\OrgProgLevel;
use app\models\OrgStudyCentreGroup;
use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrSemesterGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-semester-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $progCurr = OrgProgCurrSemester::find()
                ->joinWith(['acadSessionSemester' => function (ActiveQuery $acad) {
                    $acad->innerJoinWith(['acadSession']);
                }])
                ->joinWith(['progCurriculum'])
                ->where('true')->asArray()->all();


            $semesters = [];
            foreach ($progCurr as $sess) {
                
                $str = $sess['acadSessionSemester']['acadSession']['acad_session_name'] . ' - ';
                $str .= 'SEMESTER ' . $sess['acadSessionSemester']['semester_code']. ' - ';
                $str .= $sess['progCurriculum']['prog_curriculum_desc'];

                $semesters[] = [
                    'prog_curriculum_semester_id' => $sess['prog_curriculum_semester_id'],
                    'semester' => $str
                ];
            }
            $data = ArrayHelper::map($semesters, 'prog_curriculum_semester_id', 'semester');

            echo $form
                ->field($model, 'prog_curriculum_semester_id')
                ->label('Program Curriculum Semester', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Program Curriculum Semester...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $study = OrgStudyCentreGroup::find()
                ->select([
                    'study_centre_group_id',
                    'concat(study_centre_name,\' - \',study_group_name) AS desc'
                ])
                ->joinWith(['studyCentre', 'studyGroup'])
                ->where('true')->asArray()->all();
            $data = ArrayHelper::map($study, 'study_centre_group_id', 'desc');
            echo $form
                ->field($model, 'study_centre_group_id')
                ->label('Study Center Group', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Study Center Group ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'start_date')
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter start date ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'

                    ]
                ]);
            ?>

        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'end_date')
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter end date ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'

                    ]
                ]);
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'registration_deadline')
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter registration deadline ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'

                    ]
                ]);
            ?>

        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'display_date')
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter display date ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'

                    ]
                ]);
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $level = OrgProgLevel::find()->select(['programme_level_id', 'programme_level_name'])->all();
            $data = ArrayHelper::map($level, 'programme_level_id', 'programme_level_name');
            echo $form
                ->field($model, 'programme_level')
                ->label('Programme Level', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Programme Level ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'status')
                ->label('Status', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select a status...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>