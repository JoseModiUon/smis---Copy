<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\db\Query;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use app\models\OrgProgrammes;

use app\models\OrgAcademicSession;
use app\models\OrgUnit;
use app\models\OrgUnitHistory;
use app\models\OrgUnitTypes;
//use kartik\depdrop\DepDrop;


use yii\widgets\ActiveForm;

$this->title = 'Programme Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management/programmes']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Requirements', 'url' => ['/exam-management/programmes/programme-requirements',
    'prog_code' => $_GET['prog_code'],'prog_curriculum_id' => $_GET['prog_curriculum_id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prog-curr-group-req-course">

    <h5>
        <?= Html::encode($this->title)  ?>
    </h5>
<!--    test-->


    <hr>
    <div class="row justify-content-md-center">
        <div class="col col-md-10 bg-light">
            <div class="clearfix">&nbsp;</div>





            <div class="row text-start">




                <div class="col">


                </div>
            </div>


            <p/>


            <div class="row">
    <div class="col">
           <?php echo Html::a(
               '<i class="fa-solid fa-rotate-left"></i> Return to Programme Requirement List',
               ['/exam-management/programmes/programme-requirements',
                           'prog_code' => $_GET['prog_code'],'prog_curriculum_id' => $_GET['prog_curriculum_id']],
               ['class' => 'btn btn-outline-success btn-sm','style' => 'float:right']
           );?>
            </div><p/> <hr>

            <div class="col">
                <?php
                           echo Html::a(
                               '<i class="fa-solid"></i> Add Curriculum Course ',
                               ['/exam-management/org-prog-curr-course',
                                   'prog_code' => $_REQUEST['prog_code'],'prog_curriculum_id' => $_REQUEST['prog_curriculum_id'],
                                   'prog_study_level' => $_REQUEST['prog_study_level'], 'prog_curr_group_requirement_id' => $_GET['pcgrcid']
                                   ],
                               ['class' => 'btn btn-success btn-sm','style' => 'float:right']
                           );
?>
          </div>
            <?php


            //echo'<div class="row" > ';
            if(!empty($_REQUEST && $prog_code != 0)) {
                /*if(!$acad_session_id==0){
                $get_session=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session_id])->One();
                echo '<div class="col text-start"><strong>Academic Session: </strong>'. $get_session->acad_session_name.'</div>' ;
                }
                if(!$unit_code==0){
                $get_prog=OrgUnit::find()->select(['unit_name'])->where(['unit_code' =>$unit_code])->One();
                echo '<div class="col text-start"><strong>Unit:<br/> </strong>'. $get_prog->unit_name.'</div>' ;
                }
                if(!$acad_session==0||!empty($acad_session)){
                $get_prog=OrgAcademicLevels::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session])->One();
                echo '<div class="col text-start"><strong>Academic Level: </strong>'. $get_prog->acad_session_name.'</div>' ;
                }*/
                // exit;
                $centerContent = 'STUDENTS IN SESSION';
                $title =  'STUDENTS IN SESSION';
                //$get_unit=OrgUnit::find()->select(['unit_name'])->where(['unit_code' =>$unit_code])->One();
                //$get_session=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session_id])->One();
                $get_prog = OrgProgrammes::find()->select(['prog_full_name'])->where(['prog_code' => $prog_code])->One();

                $fileName = $get_prog->prog_code.'_students_in_session_list';
                $contentBefore = '<p style="color:#333333; font-weight: bold;">Programme: '.$get_prog->prog_full_name . '</p>';
                /* if($acad_session!=0){
                     $get_level=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session])->One();
                     $contentBefore .= '<p style="color:#333333; font-weight: bold;">Academic Level: '.$get_level->acad_session_name . '</p>';
                 }*/

                ?>

            <!--</div>-->
        </div>


        <p/>
        <?php

                ?>

        <?= GridView::widget([
                    'dataProvider' => $dataexport,
                    //  'filterModel' => $searchModel,


                    'pjax' => true,
                    'panel' => [
                        'type' => GridView::TYPE_DEFAULT,
                    ],

                    //'panel' => ['type' => 'primary', 'heading' => 'Nominal Roll Per Unit'],
                    'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                    'exportContainer' => ['class' => 'btn-group mr-2 me-2'],
                    'striped' => true,
                    'hover' => true,
                    //'showPageSummary' => true,


                    'exportConfig' => [
                        GridView::CSV => ['label' => 'CSV'],
                        // GridView::HTML =
                        // GridView::PDF => ['label' => 'Save as PDF'],
                        GridView::PDF => \app\helpers\GridExport::exportPdf([
                            'filename' => $fileName,
                            'title' => $title,
                            'subject' => 'class list',
                            'keywords' => 'class list',
                            'contentBefore' => $contentBefore,
                            'contentAfter' => '',
                            'centerContent' => $centerContent,
                            'orientation' => 'L',
                        ]),
                        GridView::EXCEL => ['label' => 'Excel']
                    ],
                    'toolbar' => [
                        '{toggleData}',
                        '{export}',

                    ],

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],




                        /*  [
                              'attribute'=>'acad_session_name',
                             // 'format'=>'raw',
                              'value'=> function ($model) {
                                 // return Html::a('Set Courses', ['', 'id'=>$model['min_group_pass']],['class'=>'btn btn-outline-success btn-sm']);
                                  return'Kanairo';
                              },
                              'group' => true,  // enable grouping
                              'groupedRow' => true,
                              'contentOptions' => [      // content html attributes for each summary cell
                                  'style' => 'color: blue',]

                          ],*/
                    /*    [
                            'attribute'=>'course_group_name',
                            'label'=>'Courses Grouping',
                            //'width'=>'150px',
                            //'headerOptions' => ['style' => 'margin-left:-100px;'],
                            'group' => true,
                            'format'=>'raw',
                            'subGroupOf'=>1,
                            'value'=> function ($model) {
                                if(!empty($model['prog_curr_level_req_id'])) {
                                    return strtoupper($model['course_group_name'])
                                        .'&nbsp;&nbsp;&nbsp; ' .Html::a('<i class="fa-solid fa-pen"></i> Edit', ['/exam-management/prog-curr-group-req/update', 'prog_curr_group_requirement_id'=>$model['prog_curr_group_requirement_id'],'prog_curr_level_req_id'=>$model['prog_curr_level_req_id'],'prog_code'=>$_REQUEST['prog_code'],'prog_curriculum_id'=>$model['prog_curriculum_id']],['style'=>'float:right'])
                                        ;}
                                else{
                                    return '-';
                                }
                            }
                        ], [
                            'attribute'=>'min_group_courses',
                            'label'=>'Minimum Courses Taken',
                            //'width'=>'10px',
                            'value'=> function ($model) {
                                if(!empty($model['prog_curr_level_req_id'])) {
                                    return strtoupper($model['min_group_courses']);
                                }
                                else{
                                    return '-';
                                }
                            }
                        ],
                        //'other_names',
                        //'gender',
                        [
                            'attribute'=>'min_group_pass',
                            'label'=>'Minimum Pass Courses',
                            //'width'=>'150px',
                            'value'=> function ($model) {
                                if(!empty($model['prog_curr_level_req_id'])) {
                                    return strtoupper($model['min_group_pass']);
                                }
                                else{
                                    return '-';

                                }
                            }
                        ],
                        [
                            'attribute'=>'buttons',
                            'label'=>' ',
                            //'width'=>'80px',
                            'format'=>'raw',
                            'value'=> function ($model) {
                                //return strtoupper($model['gender']);
                                //return '';
                                if(!empty($model['prog_curr_level_req_id'])) {
                                    return Html::a('Set Courses', ['/exam-management/programmes/prog-curr-group-req-course', 'id' => $model['prog_curr_group_requirement_id'],'prog_code'=>$_REQUEST['prog_code']], ['class' => 'btn btn-outline-success btn-sm']);
                                } else{
                                    return ' ';
                                }
                            }
                        ],
                        [
                            'attribute'=>'buttons',
                            'label'=>' ',
                            //'width'=>'80px',
                            'format'=>'raw',
                            'value'=> function ($model) {
                                //return strtoupper($model['gender']);
                                //return '';
                                if(!empty($model['prog_curr_level_req_id'])) {
                                    return Html::a('Remove', ['', 'id' => $model['min_group_pass']], ['class' => 'btn btn-outline-danger btn-sm']);
                                } else{
                                    return ' ';
                                }
                            }
                        ],*/
                        //'prog_full_name',


                        [
                            'attribute' => 'course_code',
                            'label' => 'Course',
                            //'width'=>'100px',
                            'value' => function ($model) {
                                return $model['course_code'].' - '.$model['course_name'];
                            }
                        ],


                           // 'course_name',

                           /*  /*[
                            'attribute'=>'signature',
                            'label'=>'Signature',
                            'width'=>'200px',
                            'value'=> function ($model) {
                            return ' ';
                            }
                            ],*/


                    ],
                ]);
            }?>


    </div>
</div>

</div>
