<?php

use app\modules\feesManagement\models\StudentProgrammeCurriculum;
use app\modules\studentRegistration\models\AcademicSession;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\select2\Select2Asset;
use yii2ajaxcrud\ajaxcrud\CrudAsset;
use yii\bootstrap5\Modal;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\Pjax;

Select2Asset::register($this);

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\StudentProgrammeCurriculumSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = Yii::t('app', 'Student Academic Year Progress');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fees Management'), 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = $this->title;
$request = Yii::$app->request;
$params = $request->bodyParams;
$registrationNumber = $request->get('StudentProgrammeCurriculumSearch')['registration_number'] ?? null;
$progress = 0;



$url = Url::to(['/student-records/student-academic-session/']);
$curr = Url::to([
    '/student-records/student-academic-session/index',
    'StudentProgrammeCurriculumSearch[registration_number]' => $registrationNumber
], true);


$details = new StudentProgrammeCurriculum();

$progcurrId = $details->getStudentProgCurrId($registrationNumber);
$fullName = $details->getFullName($registrationNumber);
$status = $details->getStatus($registrationNumber);
$status_id = $details->getStatusId($registrationNumber);
$module = $details->getModule($registrationNumber);
$acadProgress = $details->getAcadProgressGrid($registrationNumber);




// $query = Yii::$app->db->createCommand(
//     "
//         SELECT * FROM smis.sm_student_sem_session_progress;
//     "
// )
// ->queryAll();

// dd($query);

// var_dump($acadProgress);


$data = new ArrayDataProvider([
    'allModels' => $acadProgress,
    'key' => 'acad_session_id',
    'pagination' => [
        'pageSize' => 10,
    ],
]);


?>
<div class="student-programme-curriculum-index">
    <div class="row justify-content-center">
        <div class="col-md-10">


            <p>
                <?php Html::a(Yii::t('app', 'Create Student Programme Curriculum'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?php
            if ($registrationNumber == null && $fullName == false) {
            ?>
            <?php
                echo $this->render('_search', ['model' => $searchModel]);
            }
            ?>
            
            <?php
            if ($registrationNumber != null && $fullName != false) {
                if ($status_id[0]['status_id'] == 1) {
                    $prompt = ['Deactivate', 'Are you sure you want to deactivate the student?'];
                } else if ($status_id[0]['status_id'] == 2) {
                    $prompt = ['Activate', 'Are you sure you want to activate the student?'];
                }

            ?>



                <div class="row justify-content-center">
                    <div id="ajaxCrudDatatable">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between"></div>
                                <table class="table table-sm">

                                    <tbody>
                                        <tr>
                                            <td>
                                                <p class="text-bold">REGISTRATION NUMBER</p>
                                            </td>
                                            <td>
                                                <p class="text-bold"><?= $registrationNumber ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="text-bold">FULL NAME</p>
                                            </td>
                                            <td>
                                                <p class="text-bold"><?= $fullName ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="text-bold">PROGRAMME</p>
                                            </td>
                                            <td>
                                                <p class="text-bold"><?= $module ?></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?= GridView::widget([
                                    'dataProvider' => $data,
                                    'id' => 'crud-datatable',
                                    'responsive' => true,
                                    // 'pjax' => true,
                                    // 'filterModel' => $searchModel,
                                    'columns' => [
                                        // [
                                        //     'class' => 'kartik\grid\CheckboxColumn'
                                        // ],
                                        [
                                            'class' => 'kartik\grid\SerialColumn'
                                        ],
                                        // 'academic_progress_id',
                                        [
                                            'attribute' => 'acad_session_name',
                                            'label' => 'Academic Session Name'
                                        ],
                                        // 'academic_level_name',

                                        // 'current_status',
                                        [
                                            'attribute' => 'academic_level',
                                            'label' => 'Progress'
                                        ],
                                        // 'student_prog_curriculum_id',
                                        [
                                            'attribute' => 'progress_status_name',
                                            'label' => 'Remarks/ Status',
                                        ],
                                        // 'status',
                                    ],
                                    'toolbar' => [
                                        [
                                            'content' =>
                                            '{toggleData}' .
                                                '{export}'
                                        ],
                                    ],
                                    'panel' => [
                                        'type' => 'default',
                                        'before' => '<h5>* ' . Html::encode($this->title) . '</h5>',
                                        '<div class="clearfix"></div>',
                                    ]

                                ]); ?>
                            </div>
                        </div>

                    </div>
                </div>

            <?php
            } else if ($registrationNumber != null && isset($_GET['StudentProgrammeCurriculumSearch'])) {
            ?>


                <div class="card text-white bg-warning">
                    <img class="card-img-top" src="holder.js/100px180/" alt="">
                    <div class="card-body">
                        <h4 class="card-title">Registration number does not exist</h4>
                        <p class="card-text">Click on refresh and search for another record</p>
                    </div>
                </div>

            <?php
            }
            ?>


            <?php Pjax::end(); ?>

        </div>
        <?php Modal::begin([
            "id" => "ajaxCrudModal",
            "footer" => "",
            "clientOptions" => [
                "tabindex" => false,
                "backdrop" => "static",
                "keyboard" => false,
            ],
            "options" => [
                "tabindex" => false
            ]
        ]) ?>
        <?php Modal::end(); ?>

    </div>


    <?php Modal::begin([
        "id" => "ajaxCrudModal",
        "footer" => "", // always need it for jquery plugin
        "clientOptions" => [
            "tabindex" => false,
            "backdrop" => "static",
            "keyboard" => false,
        ],
        "options" => [
            "tabindex" => false
        ]
    ]) ?>
    <?php Modal::end(); ?>