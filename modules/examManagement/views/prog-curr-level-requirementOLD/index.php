<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\ProgCurrLevelRequirement;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProgCurrLevelRequirementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Level Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/examinationManagement']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Prog-Curr-Level-Requirement-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Level Requirements',
                    ['/examinationManagement/prog-curr-level-requirement/create','prog_curriculum_id' => Yii::$app->getRequest()->getQueryParam('prog_curriculum_id')],
                    ['class' => 'btn btn-lg btn-primary']
                )
?>
            </div>
            
            <?php

            $levels = $searchModel [0]["prog_study_level"];
for ($x = 0; $x <= 10; $x++) {
    echo "The number is: $x <br>";
}
?> 



            <h3 class="card-title mb-3">
                <?php
   // $name = $searchModel;
    $name = $searchModel [0]["prog_short_name"];
print_r($name);
?>
            </h3>

                <br>

                <h4 class="card-title mb-3">
                <?php
               // $name = $searchModel;
$level = $searchModel [0]["prog_study_level"];
print_r("Academic Study Level: ");
print_r($level);
?>
            </h4>

                <br>

                <div>
            <span>
                <?php
$coursemin = $searchModel [0]["min_courses_taken"];
print_r("Min Courses Taken: ");
print_r($coursemin);
?>
            </span>
                
            <span>
                <?php
$coursepass = $searchModel [0]["min_pass_courses"];
print_r("Min Pass Courses: ");
print_r($coursepass);
?>
            </span>
            
            <span>
                <?php
$gpacourse = $searchModel [0]["gpa_choice"];
print_r("GPA Courses: ");
print_r($gpacourse);
?>
            </span>

            <span>
                <?php
$gpaweight = $searchModel [0]["gpa_weight"];
print_r("GPA Weight: ");
print_r($gpaweight);
?>
            </span>
            <br>
                </div>


            
            <?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'export' => false,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],

       // 'prog_short_name',
       // 'prog_curriculum_id',
      //  'prog_study_level',
      //  'min_courses_taken',
       // 'pass_type',
      //  'min_pass_courses',
     //   'gpa_choice',
      //  'gpa_weight',
      //  'pass_result',
      //  'pass_recommendation',
      //  'fail_type',
        'fail_result',
        'fail_recommendation',
        'incomplete_result',
        'incomplete_recommendation',
    ],

]);
?>
        </div>
    </div>
</div>
