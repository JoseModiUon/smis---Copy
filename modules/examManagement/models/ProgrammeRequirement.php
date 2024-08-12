<?php

namespace app\modules\examManagement\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

class ProgrammeRequirement extends ActiveRecord
{
    use UserTracking;

    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public static function GET_LEVEL_REQUIREMENTS($prog_curriculum_id)
    {
        $db = self::getDb();

        /*$sql="select distinct prog_curr_level_req_id, prog_code, prog_full_name,acad_session_name,org_prog_level.programme_level_name ,org_academic_session_semester.acad_session_id,org_programme_curriculum.prog_id, prog_curr_level_requirement.prog_curriculum_id,prog_study_level, min_courses_taken,
pass_type,min_pass_courses,gpa_choice,gpa_courses,gpa_weight,pass_result,pass_recommendation,
fail_type,fail_result ,fail_recommendation ,incomplete_result,incomplete_recommendation
from smis.prog_curr_level_requirement
inner join smis.org_programme_curriculum on  org_programme_curriculum.prog_curriculum_id=prog_curr_level_requirement.prog_curriculum_id
inner join smis.org_programmes on org_programme_curriculum.prog_id =org_programmes.prog_id
inner join smis.org_prog_curr_semester on org_prog_curr_semester.prog_curriculum_id =org_programme_curriculum.prog_curriculum_id
inner join smis.org_academic_session_semester on org_academic_session_semester.acad_session_semester_id = org_prog_curr_semester.acad_session_semester_id
inner join smis.org_academic_session on org_academic_session_semester.acad_session_id =org_academic_session.acad_session_id
inner join smis.org_prog_level on org_prog_level.programme_level_id =prog_curr_level_requirement.prog_study_level
where prog_code='$prog_code' and org_academic_session_semester.acad_session_id=$acad_session";*/
        /*$sql="select distinct prog_curr_level_requirement.prog_curr_level_req_id, prog_code, prog_full_name,acad_session_name,org_prog_level.programme_level_name ,org_academic_session_semester.acad_session_id,org_programme_curriculum.prog_id, prog_curr_level_requirement.prog_curriculum_id,prog_study_level, min_courses_taken,
pass_type,min_pass_courses,gpa_choice,prog_curr_level_requirement.gpa_courses,gpa_weight,pass_result,pass_recommendation,
fail_type,fail_result ,fail_recommendation ,incomplete_result,incomplete_recommendation,prog_curr_group_requirement.prog_curr_course_group_id,prog_curr_group_requirement.min_group_courses,prog_curr_group_requirement.group_pass_type,prog_curr_group_requirement.min_group_pass,
prog_curr_group_requirement.gpa_pass_type,prog_curr_group_requirement.gpa_courses,prog_curr_group_requirement.extra_courses_processing,prog_curr_course_group.course_group_id,
prog_curr_course_group.course_group_name,prog_curr_course_group.course_group_desc,prog_curr_course_group.course_group_type,org_prog_level.programme_level_id
from smis.prog_curr_level_requirement
inner join smis.org_programme_curriculum on  org_programme_curriculum.prog_curriculum_id=prog_curr_level_requirement.prog_curriculum_id
inner join smis.org_programmes on org_programme_curriculum.prog_id =org_programmes.prog_id
inner join smis.org_prog_curr_semester on org_prog_curr_semester.prog_curriculum_id =org_programme_curriculum.prog_curriculum_id
inner join smis.org_academic_session_semester on org_academic_session_semester.acad_session_semester_id = org_prog_curr_semester.acad_session_semester_id
inner join smis.org_academic_session on org_academic_session_semester.acad_session_id =org_academic_session.acad_session_id
inner join smis.org_prog_level on org_prog_level.programme_level_id =prog_curr_level_requirement.prog_study_level
inner join smis.prog_curr_group_requirement on prog_curr_group_requirement.prog_curr_level_req_id =prog_curr_level_requirement.prog_curr_level_req_id
inner join smis.prog_curr_course_group on prog_curr_course_group.course_group_id =prog_curr_group_requirement.prog_curr_course_group_id
--inner join smis.prog_curr_group_req_course_id on prog_curr_group_req_course_id.prog_curr_group_requirement_id=prog_curr_group_requirement.prog_curr_group_requirement_id

where prog_code='$prog_code' and org_academic_session_semester.acad_session_id=$acad_session



order by org_prog_level.programme_level_id
--order by prog_curr_level_requirement.prog_curr_level_req_id
";


*/


        $sql = "SELECT   prog_curr_group_requirement.prog_curr_group_requirement_id,  
prog_curr_course_group_id, min_group_courses, group_pass_type, min_group_pass, gpa_pass_type,
prog_curr_group_requirement.gpa_courses as Prog_group_GPA_Courses, extra_courses_processing, course_group_id, course_group_name ,course_group_desc, course_group_type 
,prog_curr_group_requirement.prog_curr_level_req_id,prog_curr_level_requirement.prog_curriculum_id,
prog_study_level,prog_curr_level_requirement.min_courses_taken, prog_curr_level_requirement.pass_type, 
prog_curr_level_requirement.min_pass_courses ,prog_curr_level_requirement.gpa_choice,prog_curr_level_requirement.gpa_weight ,
prog_curr_level_requirement.pass_result,prog_curr_level_requirement.pass_recommendation,prog_curriculum_desc,
prog_curr_level_requirement.fail_type,prog_curr_level_requirement.fail_result,prog_curr_level_requirement.fail_recommendation ,
prog_curr_level_requirement.incomplete_result,prog_curr_level_requirement.incomplete_recommendation,
programme_level_id, programme_level_name,prog_curr_level_requirement.gpa_courses
FROM smis.prog_curr_group_requirement
right outer join smis.prog_curr_course_group on prog_curr_course_group.course_group_id =prog_curr_group_requirement.prog_curr_course_group_id 
right outer join smis.prog_curr_level_requirement on prog_curr_level_requirement.prog_curr_level_req_id =prog_curr_group_requirement.prog_curr_level_req_id 
right outer join smis.org_programme_curriculum on smis.org_programme_curriculum.prog_curriculum_id =prog_curr_level_requirement.prog_curriculum_id  
right outer join smis.org_prog_level on org_prog_level.programme_level_id =prog_curr_level_requirement.prog_study_level 
where org_programme_curriculum.prog_curriculum_id ='$prog_curriculum_id'
group by prog_curr_group_requirement.prog_curr_group_requirement_id,  
prog_curr_course_group_id, min_group_courses, group_pass_type, min_group_pass, gpa_pass_type,
prog_curr_group_requirement.gpa_courses, extra_courses_processing, course_group_id, course_group_name ,course_group_desc, course_group_type 
,prog_curr_group_requirement.prog_curr_level_req_id,prog_curr_level_requirement.prog_curriculum_id,
prog_study_level,prog_curr_level_requirement.min_courses_taken, prog_curr_level_requirement.pass_type, 
prog_curr_level_requirement.min_pass_courses ,prog_curr_level_requirement.gpa_choice,prog_curr_level_requirement.gpa_weight ,
prog_curr_level_requirement.pass_result,prog_curr_level_requirement.pass_recommendation,prog_curriculum_desc,
prog_curr_level_requirement.fail_type,prog_curr_level_requirement.fail_result,prog_curr_level_requirement.fail_recommendation ,
prog_curr_level_requirement.incomplete_result,prog_curr_level_requirement.incomplete_recommendation,
programme_level_id, programme_level_name,prog_curr_level_requirement.gpa_courses
order by prog_study_level

";


        $model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
    }

    public static function GET_PROG_CURRICULUM($prog_code)
    {
        $db = self::getDb();

        $sql = "select 
org_programme_curriculum.prog_id,prog_curriculum_desc,prog_curriculum_id  from smis.org_programme_curriculum
inner join smis.org_programmes on org_programmes.prog_id =org_programme_curriculum.prog_id
where prog_code ='$prog_code'";

        $model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
    }

    public static function GET_CURR_GROUP_REQ_COURSES($prog_curr_group_requirement_id)
    {
        $db = self::getDb();
        $sql = "SELECT prog_curr_group_req_course_id, prog_curr_group_req_course.prog_curr_group_requirement_id,
            prog_curr_group_req_course.prog_curriculum_course_id, prog_curr_group_req_course.credit_factor,course_code,course_name 
            FROM smis.prog_curr_group_req_course
            inner join smis.org_prog_curr_course 
            on prog_curr_group_req_course.prog_curriculum_course_id=org_prog_curr_course.prog_curriculum_course_id 
            inner join smis.org_courses on org_courses.course_id=org_prog_curr_course.course_id 
            where prog_curr_group_req_course.prog_curr_group_requirement_id ='$prog_curr_group_requirement_id'
            order by course_code ASC ";
        $model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
    }
    public static function GET_LVL_REQUIREMENT($prog_curriculum_id)
    {
        $db = self::getDb();
        $sql = "SELECT org_prog_level.programme_level_name, prog_curr_level_req_id, prog_curr_level_requirement.prog_curriculum_id, prog_study_level, min_courses_taken, pass_type, min_pass_courses, gpa_choice, gpa_courses, gpa_weight, pass_result, pass_recommendation, fail_type, fail_result, fail_recommendation, incomplete_result, incomplete_recommendation
FROM smis.prog_curr_level_requirement
inner join smis.org_prog_level on org_prog_level.programme_level_id=prog_curr_level_requirement.prog_study_level 
inner join smis.org_programme_curriculum on org_programme_curriculum.prog_curriculum_id = prog_curr_level_requirement.prog_curriculum_id 
where prog_curr_level_requirement.prog_curriculum_id =$prog_curriculum_id order by programme_level_id ASC";
        $model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
    }

    public static function GET_LVL_GROUP($prog_curr_level_req_id)
    {
        $db = self::getDb();
        $sql = "SELECT prog_curr_group_requirement_id, prog_curr_group_requirement.prog_curr_level_req_id, prog_curr_course_group_id,
min_group_courses, group_pass_type, min_group_pass, gpa_pass_type, gpa_courses, extra_courses_processing,
course_group_id, course_group_name, course_group_desc, course_group_type
FROM smis.prog_curr_group_requirement
inner join smis.prog_curr_course_group on smis.prog_curr_course_group.course_group_id =prog_curr_group_requirement.prog_curr_course_group_id 

where  prog_curr_group_requirement.prog_curr_level_req_id='$prog_curr_level_req_id'";
        $model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
    }

    public static function GET_COURSECNT($prog_curr_group_requirement_id)
    {
        $db = self::getDb();
        $sql = "SELECT count(prog_curr_group_req_course_id) as COURSES_CNT from smis.prog_curr_group_req_course  
where prog_curr_group_requirement_id='$prog_curr_group_requirement_id'";
        $model = $db->createCommand($sql);
        $data = $model->queryScalar();
        return $data;
    }
    public static function GET_COURSES($prog_curriculum_id, $prog_study_level)
    {
        $db = self::getDb();
        $sql = "SELECT org_courses.*, org_prog_curr_course.*
            FROM smis.org_courses org_courses, smis.org_prog_curr_course org_prog_curr_course
            WHERE 
                org_prog_curr_course.course_id = org_courses.course_id
                and  prog_curriculum_id='$prog_curriculum_id' and org_prog_curr_course.level_of_study ='$prog_study_level' 
                
          and org_prog_curr_course.status='ACTIVE'
                
                and prog_curriculum_course_id not in (SELECT  org_prog_curr_course.prog_curriculum_course_id
            FROM smis.prog_curr_group_req_course inner join smis.org_prog_curr_course 
            on org_prog_curr_course.prog_curriculum_course_id=prog_curr_group_req_course.prog_curriculum_course_id
            where prog_curriculum_id='$prog_curriculum_id' and org_prog_curr_course.level_of_study ='$prog_study_level'
            
           and  org_prog_curr_course.status='ACTIVE'
            )";

        //return $sql; exit;

        $model = $db->createCommand($sql);
        $data = $model->queryAll();

        return $data;
    }

}
