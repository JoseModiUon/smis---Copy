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
use app\models\OrgAcademicSession;
use app\models\OrgProgrammes;

use app\models\OrgAcademicLevels;
use app\models\OrgUnit;
use app\models\OrgUnitHistory;
use app\models\OrgUnitTypes;
//use kartik\depdrop\DepDrop;
use kartik\widgets\DepDrop;

use yii\widgets\ActiveForm;

$this->title = 'Nominal Roll Per Unit';
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
        'class' => 'form-horizontal text-center needs-validation',
        'enctype' => 'multipart/form-data',
		  'novalidate' => 'novalidate'
    ],
    'method' => 'get',
    'action' => Url::to(['']),
]);
		//Academic Session
		
	?>
   <div class="row text-start">

    
      
		    <div class="col unit_code">
   
            <?php
			//echo $unit_code;
			echo '<label class="mb-2 fw-bold">Unit</label>';
            $programme = OrgUnit::find()
                ->select(['unit_code', 'unit_name'])
				->innerJoinWith('orgUnitHistories', 'orgUnitHistories.org_unit_id = OrgUnit.unit_id')
				//->innerJoinWith('orgUnitHistories', 'orgUnitHistories.org_type_id = orgType.unit_type_id')
			//->where(['OrgUnitHistory.org_type_id'=>2])
				
				->asArray()->all();
				
				
				$query = new Query;
				$query  ->select([
							'org_unit.unit_code', 
							'org_unit.unit_name']
								)  
					->from('smis.org_unit')
        ->join('INNER JOIN', 'smis.org_unit_history',
            'smis.org_unit.unit_id=org_unit_history.org_unit_id ')      
        ->join('INNER JOIN', 'smis.org_unit_types', 
            'org_unit_types.unit_type_id =org_unit_history.org_type_id')
       // ->where('unit_type_name!="UNIVERSITY"') 
		 ->where(['<>','unit_type_name', 'UNIVERSITY'])
	   ; 

$command = $query->createCommand();
$datax = $command->queryAll();
				
				
				
            $data =  ArrayHelper::map($datax, 'unit_code', 'unit_name');
           // $data =  ArrayHelper::map($programme, 'unit_code', 'unit_name');
            echo Select2::widget([
						'name' => 'unit_code',
						'data' => $data,
						'language' => 'en',
						'value'=>$unit_code,
						'id'=>'validationCustom01',
                    'options' => ['placeholder' => '--- Select Unit ---',
									'class' => 'form-control form-control-lg unit_code',
									'required'=>'required',									
									],
					
                    'pluginOptions' => [
                      'allowClear' => true,
						  'initialize' => true,						  					  
						
                    ], 
                ]);
				
            if($unit_code==0&&!empty($_REQUEST)){
			?>
			<p/>
			<div class="alert alert-danger" role="alert">
				Select Unit!
			</div>
			<?php }?>
        </div>
		<div class="invalid-feedback">
        Please choose Academic Session.
      </div>
		<div class="col">
            <?php
			echo '<label class="mb-2 fw-bold">Academic Session</label>';
           $acad_sessions = OrgAcademicSession::find()
						->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
						
						
            $data =  ArrayHelper::map($acad_sessions, 'acad_session_id', 'acad_session_name');
            echo Select2::widget([
						'name' => 'acad_session_id',
						'data' => $data,
						'language' => 'en',
						'value'=>$acad_session_id,
						'options' => ['placeholder' => '--- Select Academic Session--',
							'class' => 'form-control form-control-lg',
						
						],
					
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
	
        </div>
       
   
   <p/>
  <?php 
   
   echo  '<div class="row">
    <div class="col">';
echo Html::submitButton('Search', ['class' => 'submit btn btn-success btn-lg', 'style'=>'float:right']) ;
echo '</div><p/> <hr>';
	
	
    ActiveForm::end();
	
//echo'<div class="row" > ';
if(!empty($_REQUEST&&$unit_code!=0&&$acad_session_id!=0)){
/*if(!$acad_session_id==0){
$get_session=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session_id])->One();
echo '<div class="col text-start"><strong>Academic Session: </strong>'. $get_session->acad_session_name.'</div>' ;
}
if(!$unit_code==0){
$get_prog=OrgUnit::find()->select(['unit_name'])->where(['unit_code' =>$unit_code])->One();
echo '<div class="col text-start"><strong>Unit:<br/> </strong>'. $get_prog->unit_name.'</div>' ;
}
if(!$acad_level==0||!empty($acad_level)){
$get_prog=OrgAcademicLevels::find()->select(['academic_level_name'])->where(['academic_level_id' =>$acad_level])->One();
echo '<div class="col text-start"><strong>Academic Level: </strong>'. $get_prog->academic_level_name.'</div>' ;
}*/
if($unit_code!=0&&$acad_session_id!=0){
	$centerContent = 'NOMINAL ROLL PER UNIT';
	$title =  'NOMINAL ROLL PER UNIT';
	$get_unit=OrgUnit::find()->select(['unit_name'])->where(['unit_code' =>$unit_code])->One();
	$get_session=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session_id])->One();
	$fileName = $get_unit->unit_name.'_'.str_replace('/','_',$get_session->acad_session_name).'_students_list';
	$contentBefore = '<p style="color:#333333; font-weight: bold;">UNIT: '.$get_unit->unit_name . '</p>';
		$contentBefore .= '<p style="color:#333333; font-weight: bold;">Academic Session: '.$get_session->acad_session_name . '</p>';
}

?>

<!--</div>-->
</div>


<p/>

   
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

            [
			   'width' => '310px',
			   
			
			'attribute'=>'prog_code',
			'label'=>'Programme',
			'value'=> function ($model) {
			return strtoupper($model['prog_code'].'-'.$model['prog_full_name']);
			},
			'group' => true,  // enable grouping,
            'groupedRow' => true,                    // move grouped column to a single grouped row
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
			],
			[
			'attribute'=>'academic_level_name',
			'label'=>'Academic Level',
			'width' => '250px',
			   'contentOptions' => ['style' => 'font-weight:bold;'],
			'value'=> function ($model) {
			return strtoupper($model['academic_level_name']);
			},
		//'group' => true,  // enable grouping,
            //'groupedRow' => true,                    // move grouped column to a single grouped row
            //'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            //'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
			],
           [
			'attribute'=>'male',
			'label'=>'Male',
			//'width'=>'10px',
			'value'=> function ($model) {
			return strtoupper($model['male']);
			}
			],
            //'other_names',
            //'gender',
			[
			'attribute'=>'female',
			'label'=>'Female',
			//'width'=>'150px',
			'value'=> function ($model) {
			return strtoupper($model['female']);
			}
			],
			
            //'prog_full_name',
			
			
			
			
			
          //  'academic_level_name',
			
					
            
        ],
]); }?>
			

        </div>
    </div>

</div>
