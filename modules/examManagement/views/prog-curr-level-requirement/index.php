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
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Prog-Curr-Level-Requirement-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Level Requirements',
                    ['/exam-management/prog-curr-level-requirement/create','prog_curriculum_id' => Yii::$app->getRequest()->getQueryParam('prog_curriculum_id')],
                    ['class' => 'btn btn-lg btn-primary']) 
                ?>
            </div>

                     
            <?php
           // $x = 0;
            //$levels = $searchModel [0]["prog_study_level"];
            //for ($x > 0; $x<$levels; $x++) {
            //echo "The number is: $x <br>";
            //}
            ?> 

            <h3 class="card-title mb-3">
                <?php 

                if (!empty($searchModel)){
                    $name = $searchModel;
                    $name = $searchModel [0]["prog_short_name"];
                    print_r ($name); 
                    
                }
                else {
                    echo "No Level requirements found. Please Create new";  
                }

                
                ?>
            </h3>

                <br>
<!--
                <h4 class="card-title mb-3">
                <?php 
               // $name = $searchModel;
             //   $level = $searchModel [0]["prog_study_level"];
               // print_r ("Academic Study Level: " ); print_r ($level); 
                ?>
            </h4>
-->
            
            <?php            
               // foreach ($searchModel as $groupValue => $groupRecords) {
                 //   echo '<h2>' . $groupValue . '</h2>';
                    echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'export' => false,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],

                       // 'prog_short_name',
                       // 'prog_curriculum_id',
                       
                        

                        [
                            'attribute' =>'academic_level_name',
                            'label' => 'Study Level',
                            'value' =>  'academic_level_name',
                        ],

                        'min_courses_taken',
                       // 'pass_type',
                        [
                            'attribute' =>'min_pass_courses',
                            'label' => 'Minimum Pass Courses',
                            'value' => 'min_pass_courses',
                        ],

                     //   'gpa_choice',
                      //  'gpa_weight',
                      //  'pass_result',
                      //  'pass_recommendation',
                      //  'fail_type',
                     //   'fail_result',
                      //  'fail_recommendation',
                      //  'incomplete_result',
                      //  'incomplete_recommendation',
                    
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => '{update} ',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    return  Html::a(' Course Grouping', ['/exam-management/prog-curr-group-requirement/index','prog_curr_level_req_id' => $model['prog_curr_level_req_id']], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                                }
                            ]
                        ],
                      
                    ],
                    
                ]); 
         //   }
            ?>
        </div>
    </div>
</div>
