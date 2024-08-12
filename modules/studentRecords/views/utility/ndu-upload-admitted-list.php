<?php

use app\models\AdmittedStudent;
use app\models\Intakes;
use app\models\IntakeSource;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var AdmittedStudent $model */

$this->title = 'NDU Admitted Students';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = 'Utility';
$this->params['breadcrumbs'][] = $this->title;

$ind = Intakes::find()->all();
$intakes = ArrayHelper::map($ind,'intake_code','intake_name');
$src = IntakeSource::find()->all();
$sources = ArrayHelper::map($src,'source_id','source');

?>
<div class="admitted-student-index row">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
            <div class="col-md-6">
                <?=
                Html::dropDownList('intake_id',null,$intakes,
                    ['id'=>'intake_id','required'=>true,'class'=>'form-select form-select-lg mb-3','prompt'=>'--* Choose Intake'])
                ?>
                <?=
                Html::dropDownList('source_id',null,$sources,
                    ['id'=>'source_id','required'=>true,'class'=>'form-select form-select-lg mb-3','prompt'=>'--* Choose Source'])
                ?>
            </div>
            <div class="col-md-6">
                <?=Html::a('Download Template',['ndu-template-download'],['class'=>'btn btn-primary btn-lg float-end','target'=>'_blank']) ?>
            </div>
            </div>
            <div class="col-6 col-sm-12">
                <?php
                echo $form->field($model, 'admit_list')->widget(FileInput::classname(), [
                    'options'=>[
                        'accept' => '.csv, 
                        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                        application/vnd.ms-excel',
                        'required'=>true,
                    ],
                    'pluginOptions' => [
                        'uploadUrl' => Url::current(),
//                        'uploadUrl' => Url::to(['upload-admitted-list']),
//                        'uploadExtraData' => new JsExpression("function () {
//                                var obj = {};
//                                obj['intake_id'] =  $('#intake').val();
//                                return obj;
//                            }
//                            "),
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false
                    ],
                    'pluginEvents' => [
                        'filepreajax'=> '(e)=> {
                            if(!$("#intake").val().length){
                                e.preventDefault(); 
                                $("#intake").focus();
                                $("button.fileinput-cancel-button").click();
                                alert("Choose an Intake!");
                                return false;
                            }
                            
                        }',
                        'fileuploaded'=> '(e)=> {
                            alert("Uploaded Successfully")
                        }',
                    ]
                ])->label(false);
                ?>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::button('Upload', ['class' => 'btn btn-primary btn-upload','type'=>'submit']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
