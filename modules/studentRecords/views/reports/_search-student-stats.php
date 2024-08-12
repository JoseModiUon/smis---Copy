<?php
/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-02-28 20:06:45 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-02-28 20:21:16
 * @Description: file:///home/wahomez/Github/smis/modules/studentRecords/views/reports/_search-student-stats.php
 */


use app\models\OrgAcademicSession;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */

/** @var yii\widgets\ActiveForm $form */
?>

<div class="student-statistics-search">

    <?php $form = ActiveForm::begin([
        'action' => ['student-statistics'],
        'method' => 'get',
    ]); ?>
    <div class="container">
        <div class="row mb-2">

            <div class="col-md-10">
                <?php
                $types = OrgAcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
                $data = ArrayHelper::map($types, 'acad_session_id', 'acad_session_name');
                echo $form
                    ->field($model, 'acad_session_id')
                    ->label('', ['class' => 'mb-2 fw-bold'])
                    ->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Academic Session ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                ?>

            </div>
            <div class="col-md-2">
                <div style="margin-top: 33px;">
                    <div class="d-flex justify-content-end">
                        <?= Html::submitButton('  Search', ['class' => 'bi bi-search btn btn-success']) ?>
                    </div>
                </div>

            </div>
        </div>





        <?php ActiveForm::end(); ?>

    </div>
</div>