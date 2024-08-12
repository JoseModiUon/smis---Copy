<?php
namespace app\modules\studentRecords\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;


class REPORTS extends ActiveRecord
{
    use UserTracking;


    public static function getDb()
    {
        return Yii::$app->get('db');
    }


	public static function GET_STUDENTS_PER_SESSION($academic_session,$prog_code)
    {



      /*  if(empty($academic_session)){
            $academic_session=0;

        }*/

        $db = self::getDb();
		
		
		$sql="SELECT sm_student.student_id, student_number,surname,
			other_names,org_academic_session.acad_session_name,
			org_academic_levels.academic_level_name,
			gender,sm_student_programme_curriculum.prog_curriculum_id,sm_academic_progress.acad_session_id,
			sm_academic_progress.academic_level_id,
			org_programme_curriculum.prog_curriculum_id,org_programmes.prog_full_name,org_programmes.prog_code
			FROM 
			smis.sm_student inner join smis.sm_student_programme_curriculum
			on sm_student_programme_curriculum.student_id=sm_student.student_id
			inner join smis.sm_academic_progress ON sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
			inner join smis.org_academic_levels 
			ON org_academic_levels.academic_level_id = sm_academic_progress.academic_level_id
			inner join smis.org_academic_session on org_academic_session.acad_session_id = sm_academic_progress.acad_session_id
			inner join smis.org_programme_curriculum on 
			sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
			inner join smis.org_programmes on org_programmes.prog_id = org_programme_curriculum.prog_id
			where sm_academic_progress.acad_session_id='$academic_session' and org_programmes.prog_code='$prog_code' 
			";
					
		
		$model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
    }
public static function GET_STUDENTS_PER_SESSION_STATS($academic_session,$prog_code)
    {



      /*  if(empty($academic_session)){
            $academic_session=0;

        }*/

        $db = self::getDb();
		
		
		$sql="select A.gender, count(A.student_id) as std_count,A.academic_level_name,A.prog_code,A.prog_full_name,A.academic_level_id  from (
SELECT sm_student.student_id, student_number,surname,
			other_names,org_academic_session.acad_session_name,
			org_academic_levels.academic_level_name,
			UPPER(gender) as gender,sm_student_programme_curriculum.prog_curriculum_id,sm_academic_progress.acad_session_id,
			sm_academic_progress.academic_level_id,
			org_programme_curriculum.prog_curriculum_id,org_programmes.prog_full_name,org_programmes.prog_code
			FROM 
			smis.sm_student inner join smis.sm_student_programme_curriculum
			on sm_student_programme_curriculum.student_id=sm_student.student_id
			inner join smis.sm_academic_progress ON sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
			inner join smis.org_academic_levels 
			ON org_academic_levels.academic_level_id = sm_academic_progress.academic_level_id
			inner join smis.org_academic_session on org_academic_session.acad_session_id = sm_academic_progress.acad_session_id
			inner join smis.org_programme_curriculum on 
			sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
			inner join smis.org_programmes on org_programmes.prog_id = org_programme_curriculum.prog_id
		 where sm_academic_progress.acad_session_id='$academic_session' and org_programmes.prog_code='$prog_code' 
			)A group by A.gender, A.academic_level_name,A.prog_code,A.prog_full_name, A.academic_level_id
			order by A.academic_level_name
			";
			
			
					
		
		$model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
    }
public static function GET_STUDENTS_PER_CLASS($academic_session,$prog_code,$acad_level)
    {
				/*if($acad_level!=0){				
				$acad_level_condition="sm_academic_progress.academic_level_id='$acad_level'";
				}
				else{
					$acad_level_condition='1=1';
				}*/
				
     $db = self::getDb();
		 
		
					$sql="SELECT sm_student.student_id, student_number,sm_student.surname,sm_student.nationality,sm_admitted_student.kcse_index_no,
			sm_student.other_names,org_academic_session.acad_session_name,
			org_academic_levels.academic_level_name,
			sm_student.gender,sm_student_programme_curriculum.prog_curriculum_id,sm_academic_progress.acad_session_id,
			sm_academic_progress.academic_level_id,
			org_programme_curriculum.prog_curriculum_id,org_programmes.prog_full_name,org_programmes.prog_code
			FROM 
			smis.sm_student inner join smis.sm_student_programme_curriculum
			on sm_student_programme_curriculum.student_id=sm_student.student_id
			inner join smis.sm_admitted_student on sm_admitted_student.adm_refno = sm_student_programme_curriculum.adm_refno

			inner join smis.sm_academic_progress ON sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
			inner join smis.org_academic_levels 
			ON smis.org_academic_levels.academic_level_id = sm_academic_progress.academic_level_id
			inner join smis.org_academic_session on org_academic_session.acad_session_id = sm_academic_progress.acad_session_id

			inner join smis.org_programme_curriculum on 

			sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
			inner join smis.org_programmes on org_programmes.prog_id = org_programme_curriculum.prog_id
			where org_programmes.prog_code='$prog_code' 
			and sm_academic_progress.acad_session_id='$academic_session'
			and sm_academic_progress.academic_level_id='$acad_level'
			
			
				";

$model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;

}
public static function GET_STUDENTS_IN_SESSION($prog_code,$acad_level)
    {		
	$db = self::getDb();
	
	if($acad_level!=0){
		
		$acad_level_condition=" and sm_academic_progress.academic_level_id='$acad_level'";
		
	}else{
		
		$acad_level_condition='';
		
	}
		 $sql="SELECT distinct sm_student.student_id, student_number,sm_student.surname,sm_student.nationality,sm_admitted_student.kcse_index_no,
			sm_student.other_names,org_academic_session.acad_session_name,
			org_academic_levels.academic_level_name,
			sm_student.gender,sm_student_programme_curriculum.prog_curriculum_id,sm_academic_progress.acad_session_id,
			sm_academic_progress.academic_level_id,
			org_programme_curriculum.prog_curriculum_id,org_programmes.prog_full_name,org_programmes.prog_code
			,org_semester_code.semester_code, org_semester_code.semster_name, CURRENT_DATE
			
			FROM 
			smis.sm_student inner join smis.sm_student_programme_curriculum
			on sm_student_programme_curriculum.student_id=sm_student.student_id
			inner join smis.sm_admitted_student on sm_admitted_student.adm_refno = sm_student_programme_curriculum.adm_refno

			inner join smis.sm_academic_progress ON sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
			inner join smis.org_academic_levels 
			ON smis.org_academic_levels.academic_level_id = sm_academic_progress.academic_level_id
			inner join smis.org_academic_session on org_academic_session.acad_session_id = sm_academic_progress.acad_session_id

			inner join smis.org_programme_curriculum on 

			sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
			inner join smis.org_programmes on org_programmes.prog_id = org_programme_curriculum.prog_id
			inner join smis.sm_student_sem_session_progress on sm_student_sem_session_progress.academic_progress_id=sm_academic_progress.academic_progress_id 
			inner join smis.org_academic_session_semester on  org_academic_session_semester.acad_session_id =org_academic_session.acad_session_id 
			inner join smis.org_semester_code on org_semester_code.semester_code=org_academic_session_semester.semester_code  
			inner join smis.org_prog_curr_semester on org_prog_curr_semester.prog_curriculum_id =sm_student_programme_curriculum.prog_curriculum_id
			inner join smis.org_prog_curr_semester_group on org_prog_curr_semester_group.prog_curriculum_semester_id = org_prog_curr_semester.prog_curriculum_semester_id  
		
			where (CURRENT_DATE between org_prog_curr_semester_group.start_date and org_prog_curr_semester_group.end_date)
				and org_programmes.prog_code='$prog_code'
				 $acad_level_condition";
	
	
	$model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
	  
	}
	public static function GET_STUDENT_STATS()
    {

     $db = self::getDb();
		 		
			/*	if($acad_level!=0){				
				$acad_level_condition="sm_academic_progress.academic_level_id='$acad_level'";
				}
				else{
					$acad_level_condition='1=1';
				}
				if($unit_code!=0){				
				$unit_code_condition="org_unit.unit_code='$unit_code'";
				}
				else{
					$unit_code_condition='1=1';
				}
				*/
					
	$sql="select 
SUM(
		CASE 
			WHEN upper(gender) = 'M'
				THEN 1
			ELSE
				0
			END) AS Male,
			SUM(CASE 
			WHEN upper(gender) = 'F'
				THEN 1
			ELSE
				0
			END) AS Female,org_academic_levels.academic_level_name,org_academic_session.acad_session_name,
			org_programmes.prog_full_name,org_unit.unit_name,org_programmes.prog_code
			
from smis.sm_student

inner join smis.sm_student_programme_curriculum on 
sm_student_programme_curriculum.student_id =sm_student.student_id 
inner join smis.org_programme_curriculum on
org_programme_curriculum.prog_curriculum_id =sm_student_programme_curriculum.prog_curriculum_id 
inner join smis.sm_academic_progress on 
sm_academic_progress.student_prog_curriculum_id =sm_student_programme_curriculum.student_prog_curriculum_id 
inner join smis.org_academic_levels on
org_academic_levels.academic_level_id =sm_academic_progress.academic_level_id 
inner join smis.org_academic_session on
org_academic_session.acad_session_id =sm_academic_progress.acad_session_id 
inner join smis.org_programmes on org_programmes.prog_id = org_programme_curriculum.prog_id
inner join smis.org_prog_curr_unit on org_prog_curr_unit.prog_curriculum_id =org_programme_curriculum.prog_curriculum_id 
inner join smis.org_unit_history  on org_prog_curr_unit.org_unit_history_id = org_unit_history.org_unit_history_id 
inner join smis.org_unit on org_unit_history.org_unit_id =org_unit.unit_id 
group by org_academic_levels.academic_level_name,org_academic_session.acad_session_name,org_programmes.prog_full_name 
,org_unit.unit_name,org_programmes.prog_code  




				";

$model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;

}
	
	
public static function GET_STUDENTS_PER_UNIT($academic_session,$unit_code,$acad_level)
    {

     $db = self::getDb();
		 		
				if($acad_level!=0){				
				$acad_level_condition="sm_academic_progress.academic_level_id='$acad_level'";
				}
				else{
					$acad_level_condition='1=1';
				}
				if($unit_code!=0){				
				$unit_code_condition="org_unit.unit_code='$unit_code'";
				}
				else{
					$unit_code_condition='1=1';
				}
				
					
	$sql="SELECT sm_student.student_id, student_number,sm_student.surname,sm_student.nationality,sm_admitted_student.kcse_index_no,
			sm_student.other_names,org_academic_session.acad_session_name,
			org_academic_levels.academic_level_name,
			sm_student.gender,sm_student_programme_curriculum.prog_curriculum_id,sm_academic_progress.acad_session_id,
			sm_academic_progress.academic_level_id,
			org_unit.unit_code,org_unit.unit_name,
			org_programme_curriculum.prog_curriculum_id,org_programmes.prog_full_name,org_programmes.prog_code
			FROM 
			smis.sm_student inner join smis.sm_student_programme_curriculum
			on sm_student_programme_curriculum.student_id=sm_student.student_id
			inner join smis.sm_admitted_student on sm_admitted_student.adm_refno = sm_student_programme_curriculum.adm_refno

			inner join smis.sm_academic_progress ON sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
			inner join smis.org_academic_levels 
			ON smis.org_academic_levels.academic_level_id = sm_academic_progress.academic_level_id
			inner join smis.org_academic_session on org_academic_session.acad_session_id = sm_academic_progress.acad_session_id

			inner join smis.org_programme_curriculum on 

			sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
			inner join smis.org_programmes on org_programmes.prog_id = org_programme_curriculum.prog_id
			inner join smis.org_prog_curr_unit on org_prog_curr_unit.prog_curriculum_id =org_programme_curriculum.prog_curriculum_id 
			inner join smis.org_unit_history  on org_prog_curr_unit.org_unit_history_id = org_unit_history.org_unit_history_id 
			inner join smis.org_unit on org_unit_history.org_unit_id =org_unit.unit_id 
			
			and $acad_level_condition
			and $unit_code_condition order by sm_academic_progress.academic_level_id ASC
				";

$model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;

}	
public static function GET_FOREIGN_STUDENTS_LIST($academic_session)
    {

     $db = self::getDb();
		 
		
					$sql="SELECT sm_student.student_id, student_number,sm_student.surname,sm_student.nationality,sm_admitted_student.kcse_index_no,
			sm_student.other_names,org_academic_session.acad_session_name,
			org_academic_levels.academic_level_name,
			sm_student.gender,sm_student_programme_curriculum.prog_curriculum_id,sm_academic_progress.acad_session_id,
			sm_academic_progress.academic_level_id,
			org_programme_curriculum.prog_curriculum_id,org_programmes.prog_full_name,org_programmes.prog_code
			FROM 
			smis.sm_student inner join smis.sm_student_programme_curriculum
			on sm_student_programme_curriculum.student_id=sm_student.student_id
			inner join smis.sm_admitted_student on sm_admitted_student.adm_refno = sm_student_programme_curriculum.adm_refno

			inner join smis.sm_academic_progress ON sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
			inner join smis.org_academic_levels 
			ON smis.org_academic_levels.academic_level_id = sm_academic_progress.academic_level_id
			inner join smis.org_academic_session on org_academic_session.acad_session_id = sm_academic_progress.acad_session_id

			inner join smis.org_programme_curriculum on 

			sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
			inner join smis.org_programmes on org_programmes.prog_id = org_programme_curriculum.prog_id
			where sm_academic_progress.acad_session_id='$academic_session'
			and upper(sm_student.nationality) !='KENYAN' 
				";

$model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;

}	

















}


























?>
