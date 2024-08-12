<?php

use app\models\OrgAcademicSession;
use app\models\OrgCountry;
use app\models\OrgProgrammes;
use app\models\SmStudentSponsor;
use app\models\Student;
use app\modules\studentRecords\models\search\StudentSearchUtility;
use kartik\select2\Select2;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

//exit;
/* @var $this yii\web\View */
/* @var $searchModel StudentSearchUtility */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $programme integer */
/* @var $session integer */

$programmes = ArrayHelper::map(OrgProgrammes::find()->all(),"prog_id",
    fn(OrgProgrammes $d)=>$d->prog_code.' '.$d->prog_full_name);

$sessions = ArrayHelper::map(OrgAcademicSession::find()->all(),"acad_session_id","acad_session_name");

$this->title = 'Search';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Utilities',];
$this->params['breadcrumbs'][] = $this->title;

$dropDOptions = ['placeholder' => '-', 'prompt' => '-', 'class' => 'form-control',];
$url = Url::to(['/student-records/utility/student-search']);
?>
<div class="student-index bg-white">
    <div class="row">
        <div class="col">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Programme</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Filter Students by Programme List below</h6>
                    <p class="card-text">
                        <?= Select2::widget([
                            'name' => 'programme',
                            'value' => ($programme?$programme:''),
                            'data' => $programmes,
                            'options' => [
                                'placeholder' => '***-Select Programme',
                                'multiple' => false,
                            ],
                            'pluginOptions' => ['allowClear' => true],
                            'pluginEvents' => [
                                "change" => "function(e) { location.href='$url?programme='+$(this).val(); }",
                            ],
                        ]);
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col col-1 d-flex justify-content-center align-items-center fs-3">-OR-</div>
        <div class="col">
            <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">Academic Session</h5>
                <h6 class="card-subtitle mb-2 text-muted">Filter Students by Academic Session List below</h6>
                <p class="card-text">
                    <?= Select2::widget([
                        'name' => 'programme',
                        'value' => ($session?$session:''),
                        'data' => $sessions,
                        'options' => [
                            'placeholder' => '***-Select Academic Session',
                            'multiple' => false,
                        ],
                        'pluginOptions' => ['allowClear' => true],
                        'pluginEvents' => [
                            "change" => "function(e) { location.href='$url?session='+$(this).val(); }",
                        ],
                    ]);
                    ?>
                </p>
            </div>
        </div>
        </div>
    </div>
<div class="clearfix">
    <?= Html::a('<i class="bi bi-arrow-clockwise"></i> Clear Filters',
        ['student-search'], ['class' => 'mt-2 me-1 btn btn-primary float-end']) ?></div>
<hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'export' => false,
        'itemLabelPlural' => 'Students', 'itemLabelSingle' => 'Student',
        'columns' => [
            ['class' => SerialColumn::class],

            'student_number',
            'service_number',
            ['attribute' => 'names','value' => fn(Student $d)=>$d->surname.' '.$d->other_names],
            ['attribute' => 'gender', 'filter' => Yii::$app->params['gender'],
                'filterInputOptions' => $dropDOptions,
            ],
//            [
//                'attribute' => 'country_code',
//                'value' => function ($d) {
//                    return $d->countryCode->nationality;
//                },
//                'filter' =>
//                    ArrayHelper::map(OrgCountry::find()->innerJoinWith(['students'])
//                        ->orderBy('org_country.nationality')->all(),
//                        "country_code", function ( $d) {
//                            return $d->nationality;
//                        }),
//                'filterInputOptions' => $dropDOptions,
//            ],
//            'dob',
            'id_no',
            'passport_no',

            // [
            //     'attribute' => 'sponsor',
            //     'filter' => ArrayHelper::map(SmStudentSponsor::find()->all(), 'sponsor_id', 'sponsor_name'),
            //     'filterInputOptions' => $dropDOptions,
            //     'value' => function (Student $e) {
            //         if($e->sponsorList->sponsor_name){
            //             return $e->sponsorList->sponsor_name;
            //         }
            //         return '--';
                    
            //     },
            // ],
//            [
//                'class' => ActionColumn::class,
//                'template' => '{student-details}',
//                'buttons' => [
//                    'student-details' => static function($url,Student $model) {
//                        return Html::a('<i class="fas fa-eye"></i>',Url::toRoute(['student-details','id'=>$model->student_id,],));
//                    }
//                ],
////                'urlCreator' => static function ($action, Student $model, $key, $index, $column) {
////                    return Url::toRoute([$action, 'STUDENT_ID' => $model->STUDENT_ID]);
////                }
//            ],
        ],
    ]) ?>


</div>
