<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrgRoomType;
use app\models\OrgBuilding;
use app\models\OrgRoomStatus;
use app\models\OrgRoomFloors;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/** @var yii\web\View $this */
/** @var app\models\OrgRooms $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-rooms-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--<?//= $form->field($model, 'room_id')->textInput() ?>-->
 <div class="row mb-2">
        <div class="col-md-12">
    <?= $form->field($model, 'room_code')->textInput(['maxlength' => true])->label('Room Code', ['class'=>'mb-2 fw-bold']) ?>
	</div>
</div>
 <div class="row mb-2">
        <div class="col-md-12">
    <?= $form->field($model, 'room_name')->textInput(['maxlength' => true])->label('Room Name', ['class'=>'mb-2 fw-bold']) ?>
	</div>
</div> 
<div class="row mb-2">
        <div class="col-md-12">
            <?php
            $buidling = OrgBuilding::find()->select(['building_id', 'building_name'])->asArray()->all();
            $datax = ArrayHelper::map($buidling, 'building_id', 'building_name');
            echo $form
                ->field($model, 'fk_building_id')
                ->label('Building', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $datax,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Building ...'],
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
            $types = OrgRoomType::find()->select(['room_type_id', 'room_type_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'room_type_id', 'room_type_name');
            echo $form
                ->field($model, 'fk_room_type')
                ->label('Room Type', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Room Type ...'],
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
            $floor = OrgRoomFloors::find()->select(['floor_id', 'floor_name'])->asArray()->all();
            $data = ArrayHelper::map($floor, 'floor_id', 'floor_name');
            echo $form
                ->field($model, 'fk_floor_id')
                ->label('Floor', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Floor ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

 <div class="row mb-2">
        <div class="col-md-12">
    <?= $form->field($model, 'room_capacity')->textInput()->label('Room Capacity', ['class'=>'mb-2 fw-bold']) ?>
</div>
</div>
   
 
<div class="row mb-2">
        <div class="col-md-12">
            <?php
            $floor = OrgRoomStatus::find()->select(['room_status_id', 'room_status_desc'])->asArray()->all();
            $data = ArrayHelper::map($floor, 'room_status_id', 'room_status_desc');
            echo $form
                ->field($model, 'fk_room_status_id')
                ->label('Room Status', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Room Status ...'],
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
