<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\helpers\Url;
use app\models\OrgAcademicSession;
use app\models\OrgProgrammes;
use app\models\OrgAcademicLevels;


use yii\widgets\ActiveForm;

$this->title = 'Nominal Roll Per Class';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Reports'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">

    <h5>
        <?= Html::encode($this->title)  ?>
    </h5>
	
	 <div class="clearfix">&nbsp;</div>
<hr>
    <div class="row justify-content-md-center">
        <div class="col col-md-10 bg-light">
		<div class="clearfix">&nbsp;</div>
		<font color='red' >Select the options provided below and click "Search" button to generate report</font>
        
		<hr/>
        
		
		
		
<?php
$form = ActiveForm::begin([
    'id' => 'active-form',
    'options' => [
        'class' => 'form-horizontal text-center',
        'enctype' => 'multipart/form-data'
    ],
    'method' => 'get',
    'action' => Url::to(['']),
]);
		//Academic Session
		
	?>
   <div class="row text-start">

		
    
      
		    <div class="col">
   
            <?php
			echo '<label class="mb-2 fw-bold">Programme</label>';
            $programme = OrgProgrammes::find()
                ->select(['prog_code', 'prog_full_name','concat(prog_code,\' - \',prog_full_name) as prog_dtl' ])->asArray()->all();
            
            $data =  ArrayHelper::map($programme, 'prog_code', 'prog_dtl');
            echo Select2::widget([
						'name' => 'prog_code',
						'data' => $data,
						'value' => $prog_code,
						'language' => 'en',
                    'options' => ['placeholder' => '--- Select Programme ---',
									'class' => 'form-control form-control-lg',],
					
                    'pluginOptions' => [
                        'allowClear' => true,
						  'initialize' => true,
						
                    ],
                ]);
            if($prog_code==0&&!empty($_REQUEST)){
			?>
			<p/>
			<div class="alert alert-danger" role="alert">
				Select Programme!
			</div>
			<?php }?>
        </div>
		<div class="col">
            <?php
			echo '<label class="mb-2 fw-bold">Academic Session</label>';
           $acad_sessions = OrgAcademicSession::find()
						->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
						
						
            $data =  ArrayHelper::map($acad_sessions, 'acad_session_id', 'acad_session_name');
            echo Select2::widget([
						'name' => 'acad_session_id',
						'value' => $acad_session_id,
						'data' => $data,
						'language' => 'en',
                    'options' => ['placeholder' => '--- Select Academic Session--','class' => 'form-control form-control-lg'],
					
                    'pluginOptions' => [
                        'allowClear' => true,
						  'initialize' => true,
						
                    ],
                ]);
             if($acad_session_id==0&&!empty($_REQUEST)){
			?>
			<p/>
			<div class="alert alert-danger" role="alert">
				Select Academic Session!
			</div>
			<?php }?>
        </div>
		<div class="col">
   
            <?php
			
			
			echo '<label class="mb-2 fw-bold">Academic Level</label>';
            $academic_level = OrgAcademicLevels::find()
                ->select(['academic_level_id', 'academic_level_name'])->orderBy([
    'academic_level_order' => SORT_ASC])->asArray()->all();
            $data =  ArrayHelper::map($academic_level, 'academic_level_id', 'academic_level_name');
            echo Select2::widget([
						'name' => 'academic_level_id',
						'data' => $data,
						'value' => $acad_level,
						'language' => 'en',
                    'options' => ['placeholder' => '--- Select Academic Level ---',
									'class' => 'form-control form-control-lg',],
					
                    'pluginOptions' => [
                        'allowClear' => true,
						  'initialize' => true,
						
                    ],
                ]);
             if($acad_level==0&&!empty($_REQUEST)){
			?>
			<p/>
			<div class="alert alert-danger" role="alert">
				Select Academic Level!
			</div>
			<?php }?>
        </div>
        </div>
       
   
   <p/>
  <?php 
  
   echo  '<div class="row">
    <div class="col">';
echo Html::submitButton('Search', ['class' => 'submit btn btn-success btn-lg', 'style'=>'float:right']) ;
echo '</div><p/> <hr>';
	
	
    ActiveForm::end();
echo'<div class="row"> ';
if(!empty($_REQUEST&&$prog_code!=0&&$acad_session_id!=0)){
	if(!$acad_session_id==0){
		$get_session=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session_id])->One();
		//echo '<div class="col text-start"><strong>Academic Session: </strong>'. $get_session->acad_session_name.'</div>' ;
	}
	if(!$prog_code==0){
		$get_prog=OrgProgrammes::find()->select(['prog_full_name'])->where(['prog_code' =>$prog_code])->One();
	//	echo '<div class="col text-start"><strong>Programme:<br/> </strong>'. $get_prog->prog_full_name.'</div>' ;
	}

	
	if($acad_session_id!=0&&$prog_code!=0){
		$centerContent = 'NOMINAL ROLL PER CLASS';
		$title =  'NOMINAL ROLL PER CLASS';
		$fileName = $get_prog->prog_full_name.'_'.str_replace('/','_',$get_session->acad_session_name).'_students_list';
		$contentBefore = '<p style="color:#333333; font-weight: bold;">Programme: '.$get_prog->prog_full_name . '</p>';
		$contentBefore .= '<p style="color:#333333; font-weight: bold;">Academic Session: '.$get_session->acad_session_name . '</p>';
		
		
	}
	if(!$acad_level==0&&!empty($acad_level)){
		$get_level=OrgAcademicLevels::find()->select(['academic_level_name'])->where(['academic_level_id' =>$acad_level])->One();
		//echo '<div class="col text-start"><strong>Academic Level: </strong>'. $get_level->academic_level_name.'</div>' ;
		$contentBefore .= '<p style="color:#333333; font-weight: bold;">Academic Level: '.$get_level->academic_level_name . '</p>';
		
	}



?>

</div>
</div>


<hr>
<p/>

   
             <?= GridView::widget([
        'dataProvider' => $dataexport,
      //  'filterModel' => $searchModel,
	  'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
		'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'exportContainer' => ['class' => 'btn-group mr-2 me-2'],
				
				
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
						'orientation'=>'L',
                    ]),
                    GridView::EXCEL => ['label' => 'Excel']
                ],
                'toolbar' => [
                    '{toggleData}',
                    '{export}',

                ],
	  
	  
	  
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'student_number',
            //'surname',

            //'other_names',
			[
			'attribute'=>'surname',
			'label'=>'Surname',
			'width'=>'100px',
			'value'=> function ($model) {
			return strtoupper($model['surname']);
			}
			],
            [
			'attribute'=>'other_names',
			'label'=>'Other Names',
			'width'=>'100px',
			'value'=> function ($model) {
			return strtoupper($model['other_names']);
			}
			],
			[
			'attribute'=>'gender',
			'label'=>'Gender',
			'width'=>'100px',
			'value'=> function ($model) {
			return strtoupper($model['gender']);
			}
			],
           // 'prog_full_name',
			[
			'attribute'=>'prog_full_name',
			'label'=>'Programme',
			'value'=> function ($model) {
			return strtoupper($model['prog_code'].'-'.$model['prog_full_name']);
			}
			],
          //  'academic_level_name',
			[
			'attribute'=>'nationality',
			'label'=>'Nationality',
			'width'=>'200px',
			'value'=> function ($model) {
			return strtoupper($model['nationality']);
			}
			],
			[
			'attribute'=>'signature',
			'label'=>'Signature',
			'width'=>'200px',
			'value'=> function ($model) {
			return ' ';
			}
			],
			
            
        ],
]); }?>
			
			

        </div>
    </div>

</div>
