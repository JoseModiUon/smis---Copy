<?php

use app\models\CrCourseRegistration;
use app\models\SmStudent;
use app\models\CrCourseRegStatus;
use app\models\CrCourseRegType;
use app\models\CrProgCurrTimetable;
use app\models\SmStudentSemSessionProgress;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\CrCourseRegistrationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var bool $regNumberIsSet */


//check if search form is empty
$get = Yii::$app->request->get();


// var_dump($_GET);exit;
        
        $regNumberIsSet = false;
        if(isset($get['CrCourseRegistrationSearch'])){
            if(!empty($get['CrCourseRegistrationSearch']['registration_number'])){
                $regNumberIsSet = true;
            }
        }

$this->title = 'Cr Course Registrations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cr-course-registration-index">

    
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <br></br>

    

    <?php
            // Check if any records were found
            if (!empty($get)) {
            $registrationNumber = Yii::$app->request->get('CrCourseRegistrationSearch')['registration_number'];
            $get_session = SmStudent::find()
                ->select(['other_names', 'surname', 'student_number'])
                ->where(['student_number' => $registrationNumber])
                ->asArray()
                ->all();

            
                echo '<div class="col text-start text-center"><strong>Student: </strong>';
                
                // Extract 'other_names' from each record and join them with a comma
                $regNumber = implode(', ', array_column($get_session, 'student_number'));
                $otherNames = implode(', ', array_column($get_session, 'other_names'));
                $surName = implode(', ', array_column($get_session, 'surname'));

                echo $regNumber;
                echo ' - ';
                echo $otherNames;
                echo ' ';
                echo $surName;
                
                echo '</div>';
            } else {
                // Handle the case where no matching records were found
                echo '<div class="col text-start text-center"><strong>No matching records found! </strong></div>';
            }
    ?>


    <?php if($regNumberIsSet) echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'student_course_reg_id',
            [
                'attribute' => 'course_reg_status_id',
                'label' => 'Course Description',
                'value' => 'course_reg_status_id'
            ],
            // 'course_reg_status_id',
            'timetable_id:datetime',
            [
                'attribute' => 'timetable_year',
                'label' => 'Year', // Customize the label as needed
                'value' => 'timetable_year'

            ],
            // 'registration_number',
            'student_semester_session_id',
            'course_registration_type_id',
            // 'registration_date',
            
            
            // 'source_ipaddress',
            // 'userid',
            'class_code',
            
            // [
            //     'class' => ActionColumn::className(),
            //     'urlCreator' => function ($action, CrCourseRegistration $model, $key, $index, $column) {
            //         return Url::toRoute([$action, 'student_course_reg_id' => $model->student_course_reg_id]);
            //      }
            // ],
        ],
    ]); ?>


</div>
