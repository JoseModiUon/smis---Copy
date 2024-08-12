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

$this->title = 'STUDENT STATISTICS PER PROGRAMME';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Reports'];
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;
$institution = $session['orgDetails']['parent_institution_name'];
?>
<div class="">

	<h4 style="text-align:center; font-weight:bold;"><?=$institution?></h4>
    <h5 style="text-align:center">
        <?= Html::encode($this->title)  ?>
    </h5>
	
	
<?php
	//Academic Session
		
	?>
	<div class="row" style="text-align:center">

		<div class="col">
   <div>
  
            <?php
			
	
  
if(!empty($_REQUEST)){
if(!$prog_code==0){
$get_prog=OrgProgrammes::find()->select(['prog_full_name'])->where(['prog_code' =>$prog_code])->One();
echo '<strong>Programme: </strong>'. $prog_code.' - '.$get_prog->prog_full_name.' ';
}
if(!$acad_session_id==0){
$get_session=OrgAcademicSession::find()->select(['acad_session_name'])->where(['acad_session_id' =>$acad_session_id])->One();
echo '<div><strong> Academic Session: </strong>'. $get_session->acad_session_name.'</div>';
}

?>
</div>
</div>
 <div class="clearfix">&nbsp;</div>
 
 <table class="table table-bordered" >

<thead>
<tr class="table-secondary">
		<th>Academic Level</th>
<?php// foreach($dt as $v){?>
		
		<th>Male</th>
		<th>Female</th>
		<th>Total</th>
		
		
		
		</tr>
		
</thead>
<tbody>
		
	<?php
        $filteredData  = [];

        foreach($dt as $index => $row) {
            if($index == 0) {
                $filteredData[] = $row;
            } else {
                foreach($filteredData as $key => $item) {
                    if($item['academic_level_id'] == $row['academic_level_id'] && $item['gender'] !== $row['gender']) {
                        $filteredData[$key] = array_merge($item, [
                            "gender2" => $row['gender'],
                            "std_count2" => $row['std_count']
                        ]);
                    } else {
						if (empty(array_filter($filteredData, fn($xitem) => $xitem['academic_level_id'] == $row['academic_level_id']))) {
							$filteredData[] = $row;

						}

                    }
                }

            }
        }
        //dd($dt, $filteredData);
		$cumTot = array_sum(array_merge(array_column($filteredData,'std_count'),array_column($filteredData,'std_count2')));
		
        foreach($filteredData as $w) { ?>
		<tr>
			<td nowrap><?= $w["academic_level_name"] ?></td>
			<?php if(array_key_exists('gender2', $w)):?>
					<?php $tot =$w["std_count"] + $w["std_count2"] ?>
					<?php if($w['gender2'] == 'M'): ?>
						<td nowrap><?= $w["std_count2"] ?></td>
						<td nowrap><?= $w["std_count"] ?></td>
					<?php else: ?>
						<td nowrap><?= $w["std_count"] ?></td>
						<td nowrap><?= $w["std_count2"] ?></td>

					<?php endif; ?>
					<td nowrap><?=$tot?></td>
					
					
			<?php else:?>
				<?php if($w['gender'] == 'M'): ?>
					<td nowrap><?= $w["std_count"] ?></td>
					<td nowrap>0</td>

				<?php else: ?>
					<td nowrap>0</td>
					<td nowrap><?= $w["std_count"] ?></td>

				<?php endif; ?>
				<td nowrap><?=$w["std_count"]?></td>
			
			<?php endif; ?>
			
		</tr>
		
	<?php } ?>	
		<tr class="table-secondary">
			<td><b>Total</b></td>
			<td></td>
			<td></td>
			<td><b><?php echo $cumTot;?></b></td>
		</tr>

       	
</tbody>

</table>
 
 <!--
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
/*echo"<pre>";
var_dump($dt);
echo"</pre>";
exit;*/
/*
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

	<tr><td></td>
				<td nowrap></td>
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
		
			echo'<tr class="table-secondary"><td></td><td></td><td><b>Total</b> </td><td><b>'.count($res).'</b></td></tr>';
			
		
		*/
		?>
		<tr>
		
</tbody>
</table>-->

<p/>


 
           <?php } ?>
			
			

        </div>
    </div>

</div>
