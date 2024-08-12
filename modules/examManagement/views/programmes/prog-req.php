<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use Yii;
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
use app\modules\examManagement\models\ProgrammeRequirement;
//use kartik\depdrop\DepDrop;


use yii\widgets\ActiveForm;

$this->title = 'Programme Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management/programmes','prog_code' => $_REQUEST['prog_code']]];

$this->params['breadcrumbs'][] = $this->title;


//dd($lvl_requirement);
?>
<style>
    .headerBG{
        background-color: #ECD3D0;
    }
    .groupBg{
        font-style:italic;
        background-color: #F2F2F2;
        font-weight: bolder;
    }

</style>
<div class="">

    <h5>
        <?= Html::encode($this->title)  ?>
    </h5>
    <div class="row justify-content-md-center">
        <div class="col col-md-10 bg-light">
            <div class="clearfix">&nbsp;</div>
<div class="row">
    <div class="col col-md-12">
         <?php
         $request = Yii::$app->request;
echo Html::a(
    '<i class="fa-solid fa-rotate-left"></i> Return to Programme Curriculum List',
    ['/exam-management/programmes/',
            'prog_code' => $request->get('prog_code')],
    ['class' => 'btn btn-outline-success btn-sm','style' => 'float:right']
);?>
</div>
</div>
            <div class="clearfix">&nbsp;</div>

    <table class="table table-bordered">
<!--    <thead>-->
<!--    <tr>-->
<!--        <th scope="col">#</th>-->
<!--        <th scope="col"></th>-->
<!--        <th scope="col">Last</th>-->
<!--        <th scope="col">Handle</th>-->
<!--    </tr>-->
<!--    </thead>-->
    <tbody>
    <tr><td colspan="5">
            <?php
   echo Html::a(
       '<i class="fa-solid fa-plus"></i> Add Level Requirement',
       ['/exam-management/prog-curr-level-req/create', 'prog_code' => $_REQUEST['prog_code'],'prog_curriculum_id' => $_REQUEST['prog_curriculum_id']],
       ['class' => 'btn btn-success btn-sm','style' => 'float:right']
   );


if(count($lvl_requirement) > 0) {

    foreach ($lvl_requirement as $Lvlrow) {
        echo '<tr class="headerBG"><td colspan="5"><h5>Study Level / Part : ' . $Lvlrow['programme_level_name'] . '</h5>
            <br/><font color="blue">Min Courses taken=' . $Lvlrow['min_courses_taken'] . ' | 
                    Min Pass Courses=' . $Lvlrow['min_pass_courses'] . ' |  GPA Courses=' . $Lvlrow['gpa_courses'] . '  
                | GPA Weight=' . $Lvlrow['gpa_weight'] . '</font> &nbsp;&nbsp;'.
            Html::a('<i class="fa fa-pen"></i> Edit Level Requirement', ['/exam-management/prog-curr-level-req/update',
                'prog_curr_level_req_id' => $Lvlrow['prog_curr_level_req_id'],
                'prog_code' => $_REQUEST['prog_code'],
                'prog_curriculum_id' => $Lvlrow['prog_curriculum_id']], ['class' => 'btn btn-warning btn-sm'])

             .' </td>
               </tr>
               <tr>
                <td colspan="5">'
            . Html::a('<i class="fa-solid fa-plus"></i> Add Curriculum Group Requirement', ['/exam-management/prog-curr-group-req/create',
                'prog_curr_level_req_id' => $Lvlrow['prog_curr_level_req_id'], 'prog_code' => $_REQUEST['prog_code'],
             'prog_curriculum_id' => $Lvlrow['prog_curriculum_id']], ['class' => 'btn btn-outline-primary btn-sm', 'style' => 'float:right']).'              
                
                
                <tr>';

        //            $count_lvlgrp = ProgrammeRequirement::GET_LVL_GROUP_COUNT($Lvlrow['prog_curr_level_req_id']);


        $lvlgrp = ProgrammeRequirement::GET_LVL_GROUP($Lvlrow['prog_curr_level_req_id']);

        if (count($lvlgrp) > 0) {

            echo '<tr class="groupBg"><td>Course Grouping</td> <td>Min Courses Taken</td><td>Min Pass Courses</td><td></td>
            <td ><i class="fa-solid fa-triangle-exclamation" style="color:red"></i> In order to remove <br/>course grouping,<br/> remove courses first.</i></td></tr>';
            foreach ($lvlgrp as $lvlgrprow) {
                echo '<tr>
                          
                            <td>' . $lvlgrprow['course_group_name']
                      .Html::a('<i class="fa-solid fa-pen"></i> Edit', ['/exam-management/prog-curr-group-req/update',
                        'prog_curr_group_requirement_id' => $lvlgrprow['prog_curr_group_requirement_id'],
                        'prog_curr_level_req_id' => $Lvlrow['prog_curr_level_req_id'],
                        'prog_code' => $_REQUEST['prog_code'],
                        'prog_curriculum_id' => $Lvlrow['prog_curriculum_id']], ['style' => 'float:right']).'</td>
                            
                            <td>' . $lvlgrprow['min_group_courses'] . ' </td> 
                            <td>' . $lvlgrprow['min_group_pass'] . ' </td> 
                            <td>'.Html::a('Set Courses', ['/exam-management/prog-curr-grp-req-course',
                         'id' => $lvlgrprow['prog_curr_group_requirement_id'],'prog_code' => $_REQUEST['prog_code'],
                         'prog_curriculum_id' => $_REQUEST['prog_curriculum_id'],'prog_study_level' => $Lvlrow['prog_study_level'], 'pcgrcid' => $lvlgrprow['prog_curr_group_requirement_id']], ['class' => 'btn btn-outline-success btn-sm']).'
                            <i style="float:right; color:green"> Courses Set: '.$courseCNT = ProgrammeRequirement::GET_COURSECNT($lvlgrprow['prog_curr_group_requirement_id']).'</i></td>
                           ';
                if($courseCNT >= 1) {
                    echo'<td></td>';
                } else {


                    echo'<td>'
                    . Html::a(
                        '<i class="fa-solid fa-trash"></i> Remove',
                        ['/exam-management/prog-curr-group-req/delete',
                            'prog_curr_group_requirement_id' => $lvlgrprow['prog_curr_group_requirement_id'],
                            'prog_curriculum_id' => $Lvlrow['prog_curriculum_id'],
                            'prog_curr_level_req_id' => $Lvlrow['prog_curr_level_req_id'],
                            'prog_code' => $_REQUEST['prog_code'],],
                        [
                                'class' => 'btn btn-outline-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to remove '.$lvlgrprow['course_group_name'],
                                    'method' => 'post',]

                            ]
                    ).'

                        </td>
                        </tr>';
                }
            }
        } else {
            echo ' <tr class="groupBg"><td colspan="5" style="text-align: center">No Curriculum Group Requirement(s) Set</td></tr>';


        }


        //}
    }
} else {
    echo ' <tr><td colspan="5" style="text-align: center;"><div class="alert alert-warning" role="alert">
 <b>No Records found</b></div></td></tr>';
}
?>




    </tbody>
</table>



        </div>
    </div>

</div>