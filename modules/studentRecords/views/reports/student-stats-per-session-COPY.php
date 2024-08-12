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


use yii\widgets\ActiveForm;

$this->title = 'Student Statistics Per Programme';
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
	<div class="row" style="text-align:left">
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
						'data' => $data,
						'language' => 'en',
						'value'=>$acad_session_id,
                    'options' => ['placeholder' => '--- Select Academic Session ---','class' => 'form-control form-control-lg'],
					
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
	
	
	
if(!empty($_REQUEST&&$acad_session_id!=0&&$prog_code!=0)){
	if(count($dt)<1){
		echo '<div class="alert alert-dark" role="alert">
  No Records Found
</div>';
	}
	else{

$get_session=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session_id])->One();
//echo '<div style="text-align:center"><strong>Academic Session: </strong>'. $get_session->acad_session_name.'</div>' ;


$get_prog=OrgProgrammes::find()->select(['prog_full_name'])->where(['prog_code' =>$prog_code])->One();
//echo '<div style="text-align:center"><strong>Programme: </strong>'. $get_prog->prog_full_name.'</div>' ;

echo "<div style=text-align:right>";
	echo Html::a('<i class="bi bi-printer"></i> Print Report', ['/student-records/reports/student-stats-per-session-pdf',
	'prog_code'=>$prog_code,'acad_session_id' =>$acad_session_id, 'acad_session'=>$get_session->acad_session_name], [
    'class'=>'btn btn-outline-secondary', 
    'target'=>'_blank', 
    'data-toggle'=>'tooltip', 
    'title'=>'Will open the generated PDF file in a new window'
]);
echo "</div>";
	
?>
 <div class="clearfix">&nbsp;</div>
</div>
<table  align= "center" class="table table-bordered" style="line-height: 24px;">
		<thead>
			<tr class="table-secondary">
			<th></th>
				<th>Academic Level</th>
				<th>Gender</th>
				<th>No. of Students</th>
			
			</tr>
		</thead>
		<tbody>
<?php 


function addGender($row,$datax,$i){
	
	$gender=$row['gender'];
	$display_gender='';
	if(strtolower($gender)=='m'){
		$display_gender='f';
		
	}elseif(strtolower($gender)=='f'){
		$display_gender='m';
		
	}
	
	
	$academic_level_id=$row['academic_level_id'];
	$items=array_filter($datax,function($item)use ($gender,$academic_level_id){
		return $item['academic_level_id']==$academic_level_id;
	});
	//dd($items);
	 if(count($items)==1){
		 
	 ?>

	<tr><td><?//=++$i;?></td>
				<td nowrap><?//= $row["academic_level_name"] ?></td>
				<td nowrap><?=strtoupper($display_gender);		?></td>						

				
				<td nowrap>0</td>
		
			</tr>
	
	 <?php 	}
}



$res=$dt;
$i=0;
foreach($res as $k){
	
			?>
			<tr><td><?=++$i;?></td>
				<td nowrap><?= $k["academic_level_name"] ?></td>
				<td nowrap><?=strtoupper($k["gender"]);		?></td>						

				
				<td nowrap><?=$k["std_count"]?>
		
			</tr>
			<?php
		addGender($k,$res,$i);
		 
		}
		
			echo'<tr class="table-secondary"><td></td><td></td><td><b>Total</b> </td><td>'.count($res).'</td></tr>';
			
		
		
		?>
		<tr>
		
</tbody>
</table>
<hr>
<p/>


 
	<?php } }?>
			
			

        </div>
    </div>

</div>
