<?php

/** @var yii\web\View $this */

/** @var $model Student */
/** @var $canEdit bool Check if to enable editing */

use app\models\OrgCountry;
use app\models\SmStudentSponsor;
use app\models\Sponsor;
use app\models\Student;
use kartik\editable\Editable;
//use Stidges\CountryFlags\CountryFlag;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$countries = ArrayHelper::map(OrgCountry::find()->asArray()->all(), 'code','nationality');
$sponsors = ArrayHelper::map(SmStudentSponsor::find()->asArray()->all(), 'sponsor_id', 'sponsor_name');
$cnf = Yii::$app->params;

$bloodGrp = array_combine($cnf['bloodGrp'], $cnf['bloodGrp']);

$Uurl = Url::to(['personal-info', 'id' => $model->student_id]);

$nameEdit = function () use($model,$Uurl) {
    $editable = Editable::begin(config: [
        'model' => $model,
        'attribute' => 'surname', 'format' => Editable::FORMAT_BUTTON,
        'showButtonLabels' => true,
        'inputType' => Editable::INPUT_TEXT,
        'inlineSettings' => [
            'templateBefore' => Editable::INLINE_BEFORE_2,
            'templateAfter' => Editable::INLINE_AFTER_2
        ],
        'asPopover' => false, 'formOptions' => ['action' => $Uurl],
        'displayValue' => $model->surname . ' ' . $model->other_names,
        'pluginEvents' => [
            "editableSuccess" => '(event, val, form, data)=>{$(".stdn-name").html(data.output);}',
        ],
    ]);
    $form = $editable->getForm();
    $editable->afterInput =
        '' . $form->field($model, 'other_names')->textInput();
    Editable::end();
};
?>

    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div>
                                <img src="<?= $model->avatar() ?>"
                                     alt="Profile"
                                     class="rounded mx-auto d-block img-fluid stdn-avtr" style="width: 100px;">
                                <?php if ($canEdit): ?>
                                    <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#stdn-photo-upload"
                                            style="position: absolute; bottom: 30%; right: 24%; cursor: pointer;"
                                            class="btn btn-sm btn-outline-secondary kv-editable-button kv-editable-toggle">
                                        <i class="fas fa-pencil-alt"></i></button>
                                <?php endif; ?>

                            </div>
                            <h5 class="stdn-name my-3"><?= $model->surname . ' ' . $model->other_names; ?></h5>
                            <p class="text-muted mb-1"></p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Sponsor</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">

                                        <?php
                                        $sponsorName = '';
                                        if(!is_null($model->sponsorList)){
                                            $sponsorName = $model->sponsorList->sponsor_name;
                            
                                        }
                                        echo $canEdit ? Editable::widget([
                                            'model' => $model, 'format' => Editable::FORMAT_BUTTON,
                                            'attribute' => 'sponsor', 'showButtonLabels' => true,
                                            'displayValueConfig' => $sponsorName,
                                            'displayValue' => $sponsorName,
                                            'asPopover' => false,
                                            'inputType' => Editable::INPUT_SELECT2,
                                            'data' => $sponsors, 'formOptions' => ['action' => $Uurl],
                                            'options' => [
                                                'data' => $sponsors, 'pluginOptions' => ['allowClear' => false, 'placeholder' => 'Choose Sponsor..',],
                                            ],
                                            'pluginEvents' => [
                                                'editableSuccess' => '(event, val, form, data)=>{
                                                $(".stdn-sponsor").html(data.output);
                                            }',
                                            ],
                                        ]) : $sponsorName;
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Full Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">
                                        <?=$canEdit ? $nameEdit() : $model->surname . ' ' . $model->other_names?>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Student Number</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?= $model->student_number ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Gender</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">
                                        <?= $canEdit ? Editable::widget([
                                            'model' => $model, 'formOptions' => ['action' => $Uurl],
                                            'attribute' => 'gender', 'showButtonLabels' => true,
                                            'asPopover' => false, 'format' => Editable::FORMAT_BUTTON,
                                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                            'data' => $cnf['gender'],
                                            'options' => ['class' => 'form-control', 'prompt' => 'Select Gender...'],
                                            'displayValueConfig' => [
                                                'M' => '<i class="fa-solid fa-male"></i> MALE',
                                                'F' => '<i class="fa-solid fa-female"></i> FEMALE',
                                            ],
                                        ]) : $cnf['gender'][$model->gender];
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Blood Group</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">
                                        <?= $canEdit ? Editable::widget([
                                            'model' => $model, 'format' => Editable::FORMAT_BUTTON,
                                            'attribute' => 'blood_group', 'showButtonLabels' => true,
                                            'asPopover' => false,
                                            'inputType' => Editable::INPUT_SELECT2,
                                            'data' => $bloodGrp, 'formOptions' => ['action' => $Uurl,],
                                            'options' => [
                                                'data' => $bloodGrp,
                                                'pluginOptions' => [
                                                    'allowClear' => true, 'placeholder' => 'Select Blood Group',
                                                ],
                                            ],
                                            'pluginEvents' => [
                                                'editableSuccess' => '(event, val, form, data)=>{
                                                $(".stdn-blood").html(val);
                                            }',
                                            ],
                                        ]) : $model->blood_group??'~';
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Nationality</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">
                                        <?= $canEdit ? Editable::widget([
                                            'model' => $model, 'format' => Editable::FORMAT_BUTTON,
                                            'attribute' => 'country_code', 'showButtonLabels' => true,
                                            'displayValueConfig' =>  $model->countryCode->nationality,
                                            'displayValue' => $model->countryCode->nationality,
                                            'asPopover' => false,
                                            'inputType' => Editable::INPUT_SELECT2,
                                            'data' => $countries, 'formOptions' => ['action' => $Uurl,],
                                            'options' => [
                                                'data' => $countries,
                                                'pluginOptions' => [
                                                    'allowClear' => false, 'placeholder' => 'Choose Country..',
                                                ],
                                            ],
                                            'pluginEvents' => [
                                                'editableSuccess' => '(event, val, form, data)=>{
                                                $(".stdn-nationality").html(data.output);
                                            }',
                                            ],
                                        ]) : $model->countryCode->nationality;
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">National ID No.</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">
                                        <?= $canEdit ? Editable::widget([
                                            'model' => $model, 'format' => Editable::FORMAT_BUTTON,
                                            'attribute' => 'id_no', 'showButtonLabels' => true,
                                            'asPopover' => false, 'formOptions' => ['action' => $Uurl],
                                        ]) : $model->id_no ?? '~';
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Passport No</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">
                                        <?= $canEdit ? Editable::widget([
                                            'model' => $model, 'format' => Editable::FORMAT_BUTTON,
                                            'attribute' => 'passport_no', 'showButtonLabels' => true,
                                            'asPopover' => false, 'formOptions' => ['action' => $Uurl],
                                        ]) : $model->passport_no ?? '~';
                                        ?></p>
                                </div>
                            </div>
                            <hr>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php


$this->registerCss(
    <<<CSS
.field-student-nationality > .select2-container
{ width: 250px !important;}
CSS

);
