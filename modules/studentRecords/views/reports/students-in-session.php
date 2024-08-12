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

$this->title = 'Students in Session';
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

    
      
	<div class="col">
   
            <?php
        echo '<label class="mb-2 fw-bold">Programme</label>';
$programme = OrgProgrammes::find()
    ->select(['prog_code', 'prog_full_name','concat(prog_code,\' - \',prog_full_name) as prog_dtl' ])->asArray()->all();
$data =  ArrayHelper::map($programme, 'prog_code', 'prog_dtl');
echo Select2::widget([
            'name' => 'prog_code',
            'data' => $data,
            'language' => 'en',
            'value'=>$prog_code,
        'options' => ['placeholder' => '--- Select Programme ---',
                        'class' => 'form-control form-control-lg',],

        'pluginOptions' => [
            'allowClear' => true,
              'initialize' => true,

        ],
    ]);
if($prog_code==0&&!empty($_REQUEST)) {
    ?>
			<p/>
			<div class="alert alert-danger" role="alert">
				Select Programme!
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
						'value'=>$acad_level,
						'language' => 'en',
                    'options' => ['placeholder' => '--- Select Academic Level ---',
									'class' => 'form-control form-control-lg',],
					
                    'pluginOptions' => [
                        'allowClear' => true,
						  'initialize' => true,
						
                    ],
                ]);
            ?>
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
if(!empty($_REQUEST&&$prog_code!=0)){
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

	$centerContent = 'STUDENTS IN SESSION';
	$title =  'STUDENTS IN SESSION';
	//$get_unit=OrgUnit::find()->select(['unit_name'])->where(['unit_code' =>$unit_code])->One();
	//$get_session=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session_id])->One();
	$get_prog=OrgProgrammes::find()->select(['prog_full_name'])->where(['prog_code' =>$prog_code])->One();
	
	$fileName = $get_prog->prog_code.'_students_in_session_list';
	$contentBefore = '<p style="color:#333333; font-weight: bold;">Programme: '.$get_prog->prog_full_name . '</p>';
	if($acad_level!=0){
	$get_level=OrgAcademicLevels::find()->select(['academic_level_name'])->where(['academic_level_id' =>$acad_level])->One();
		$contentBefore .= '<p style="color:#333333; font-weight: bold;">Academic Level: '.$get_level->academic_level_name . '</p>';
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
			return strtoupper($model['prog_code'].'-'.$model['prog_full_name'].'   ('.$model['acad_session_name'].'  -'.$model['semster_name'].')' );
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
			return strtoupper('Level of Study: '.$model['academic_level_name']);
			},
			'group' => true,  // enable grouping
			'groupedRow' => true,
			],
           [
			'attribute'=>'student_number',
			'label'=>'Student Number.',
			//'width'=>'150px',
			//'headerOptions' => ['style' => 'margin-left:-100px;'],
			'value'=> function ($model) {
			return strtoupper($model['student_number']);
			}
			], [
			'attribute'=>'surname',
			'label'=>'Surname',
			//'width'=>'10px',
			'value'=> function ($model) {
			return strtoupper($model['surname']);
			}
			],
            //'other_names',
            //'gender',
			[
			'attribute'=>'other_names',
			'label'=>'Other Names',
			//'width'=>'150px',
			'value'=> function ($model) {
			return strtoupper($model['other_names']);
			}
			],
			[
			'attribute'=>'gender',
			'label'=>'Gender',
			//'width'=>'80px',
			'value'=> function ($model) {
			return strtoupper($model['gender']);
			}
			],
            //'prog_full_name',
			
			
			
			
			
          //  'academic_level_name',
			[
			'attribute'=>'nationality',
			'label'=>'Nationality',
			//'width'=>'100px',
			'value'=> function ($model) {
			return strtoupper($model['nationality']);
			}
			],
			/*[
			'attribute'=>'signature',
			'label'=>'Signature',
			'width'=>'200px',
			'value'=> function ($model) {
			return ' ';
			}
			],*/
					
            
        ],
]); }?>
			

        </div>
    </div>

</div>
