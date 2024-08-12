<?php
/**
 *  
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $regNumber
 */

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CrCourseRegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $regNumber string|null */ // The $regNumber variable passed from the controller

use app\models\SmStudent;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\web\ServerErrorHttpException;
use yii\widgets\ActiveForm;


$this->title = $title;

$this->title = 'Student Course Registration';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration Records', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = ['label' => 'Reports'];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
         <h5>
        <?= Html::encode($this->title)  ?>
    </h5>
    </div>
</div>
	
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-10 offset-1">
			    <div class="card">
                    <div class="card-title text-danger">
                        <div>Enter the student registration number in the field provided below and  click "Search" button to generate report</div>
                    </div>
                    <div class="card-body">
                        <?php $form = ActiveForm::begin([
                            'action' => ['courses'],
                            'method' => 'get',
                        ]); ?>

                        <div class="row">
                            <div class="col-10">
                                <?= $form->field($coursesSearchModel, 'registration_number')
                                    ->textInput(['placeholder' => 'Enter Student Registration Number','required'=>true])
                                    ->label(false) ?>
                            </div>
                            <div class="col-2">
                                <?= Html::submitButton('Search', ['class' => 'btn btn-success','style'=>'float:right']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-10 offset-1">
                <?php
                 $student = SmStudent::find()
                    ->select(['other_names', 'surname'])
                    ->where(['student_number' => $regNumber])
                    ->asArray()
                    ->one();
                
                    if(!empty($student)):
                        $stundetName = $student['surname'] . ' ' . $student['other_names'];
                ?>

                <div class="col text-start text-center">
                    <strong>Student: <?= $regNumber . ' - ' . $stundetName;?></strong>
                </div>
              
                <?php endif;?>
            </div>
        </div>

        <div class="row">
            <div class="col-10 offset-1">
                <?php if($regNumber):
      
                $regIdCol = [
                    'label' => 'Course Description',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['course_name'];
                    }
                ];
                $regDateCol = [
                    'label' => 'Year',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['acad_session_name'];
                    }
                ];
                $ipCol = [
                    'attribute' => 'source_ipaddress',
                    'label' => 'Sem',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['semester'];
                    }
                ];
                $levelCol = [
                    'label' => 'Exam Type',
                    'attribute' => 'pcsg.programme_level',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['course_reg_type_code'];
                    }
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $regIdCol,
                    $regDateCol,
                    $ipCol,
                    $levelCol
                ];

                $toolbar = [
                    '{export}',
                    '{toggleData}',
                ];

                try{
                    echo GridView::widget([
                        'id' => 'documents-grid',
                        'dataProvider' => $coursesProvider,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => $toolbar,
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => [
                            'fontAwesome' => true,
                            'label' => 'Export student'
                        ],
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 50],
                        'itemLabelSingle' => 'student',
                        'itemLabelPlural' => 'students',
                    ]);
                }catch (Throwable $ex) {
                    $message = $ex->getMessage();
                    if(YII_ENV_DEV) {
                        $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                    }
                    throw new ServerErrorHttpException($message, 500);
                }
                ?>
                <?php endif;?>
            </div>
        </div>

    </div>
</section>


