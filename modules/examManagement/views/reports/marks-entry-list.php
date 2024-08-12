<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgRooms;
use app\models\OrgDays;
use app\models\OrgProgCurrSemester;
use app\models\OrgProgCurrSemesterGroup;
use app\models\OrgProgLevel;
use app\models\OrgSemesterCode;
use app\models\CrProgCurrTimetable;
use app\models\OrgAcademicSession;
use app\models\OrgCourses;
use app\models\OrgProgCurrUnit;
use app\models\StudentProgrammeCurriculum;
use kartik\dialog\Dialog;
use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

echo Dialog::widget([
    'libName' => 'krajeeDialogCust',
    'options' => ['draggable' => true, 'closable' => true], // custom options
]);


$this->title = 'Marks Entry';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
$course = OrgCourses::findOne(Yii::$app->request->get('course_id'));
$acad = OrgAcademicSession::findOne(Yii::$app->request->get('acad_session_id'));
$unit = OrgProgCurrUnit::find()->select(['org_unit.*'])
    ->joinWith(['orgUnitHistory' => function (ActiveQuery $or) {
        $or->joinWith(['orgUnit']);
    }])
    ->where([
        'prog_curriculum_id' => Yii::$app->request->get('prog_curriculum_id')
    ])->asArray()->one();


function getGrade($reg_no, $final_mark)
{

    if ($final_mark) {
        $grading = StudentProgrammeCurriculum::find()
            ->select('grade')
            ->innerJoinWith(['student' => function (ActiveQuery $stu) use ($reg_no) {
                $stu->where(['registration_number' => str_replace("_", "/", $reg_no)]);
            }])
            ->innerJoinWith(['progCurriculum' => function (ActiveQuery $pr) {
                $pr->innerJoinWith(['gradingSystem' => function (ActiveQuery $prog) {
                    $prog->innerJoinWith(['gradingDetail']);
                }]);
            }])
            ->innerJoinWith(['smAcademicProgresses' => function (ActiveQuery $acad) {
                $acad->innerJoinwith(['academicLevel']);
            }])
            ->where(['>=', 'upper_bound', $final_mark])
            ->andWhere(['<=', 'lower_bound',  $final_mark])
            ->asArray()
            ->one();

        return $grading['grade'];
    }
    return '';
}
$courseName = $course->course_name;
$courseCode = $course->course_code;
$academicYear =  $acad->acad_session_desc;
$deptName = $unit['unit_name'];

$title = $courseName . ' (' . $courseCode . ') CLASS REGISTER';
$fileName = $courseCode . '_classlist';
$centerContent = 'CLASS REGISTER';

$title = $courseName . ' (' . $courseCode . ') CLASS REGISTER';
$fileName = $courseCode . '_classlist';
$centerContent = 'CLASS REGISTER';

$contentBefore = '<p style="color:#333333; font-weight: bold;">Academic year: ' . $academicYear . '</p>';
$contentBefore .= '<p style="color:#333333; font-weight: bold;">Course: ' . $courseName . ' (' . $courseCode . ')</p>';
$contentBefore .= '<p style="color:#333333; font-weight: bold;">Department: ' . $deptName . '</p>';
$contentBefore .= '<p style="color:#333333; font-weight: bold;">Date: ........................ Time: ........................</p>';
$contentAfter = '<div style="position: absolute; bottom: 80px;">
<span>Lecture</span> ..........................
<span>Sign</span> ............................
<span>Date</span> ............................
<hr>
<div style="text-align:center"><span style="font-weight:bold">For Official Use</span> </div>
<div>
    <span>Sheet</span> ..........................
    <span>of</span> ............................
</div>

<div>
    <span> For Last Sheet, provide summary as follows : Total No in Session</span> .......................................................               
</div>
</div>';

?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">
            <div class="text-justify">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
                <p><span style="font-weight: bold;">Academic Year:</span> <?= $academicYear ?></p>
                <p><span style="font-weight: bold;">Course:</span> <?= "$courseName({$courseCode})" ?></p>
                <p class="mb-2"><span style="font-weight: bold;">Department:</span> <?= $deptName ?></p>
            </div>


            <?php


            echo GridView::widget([
                'id' => 'marks-entry-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'exportContainer' => ['class' => 'btn-group mr-2 me-2'],
                'export' => false,
                'toolbar' => [
                    '{toggleData}',
                    [
                        'content' =>
                        Html::button(
                            '<i class="fas fa-check"></i> Save Marks',
                            [
                                'title' => 'Save Marks',
                                'id' => 'save-marks-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]
                        ).                        Html::button(
                            '<i class="fas fa-check"></i> Save Template',
                            [
                                'title' => 'Save Template',
                                'id' => 'save-temp-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]
                        ),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                ],

                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //. No. Surname Other Names Student Email Student Mobile
                    [
                        'label' => 'Student Details',
                        'attribute' => 'student_number',
                        'value' => function ($model) {
                            return $model['student_number'] . ' -' . $model['surname'] . ' ' . $model['other_names'];
                        }
                    ],
                    [
                        'attribute' => 'course_work_mark',
                        'format' => 'raw',

                        'value' => function ($model) {
                            $reg_no = str_replace("/", "_", $model['student_number']);
                            $marksheet_id = $model['marksheet_id'];
                            $timetable_id = $model['timetable_id'];
                            $course_work_mark = $model['course_work_mark'];
                            return '<input placeholder="0" name="course_work_mark[]" id="' . $reg_no . '_course_work_mark" type="text" class="form-control" marksheet="' . $marksheet_id . '" timetable="' . $timetable_id . '" value="' . $course_work_mark . '"/>';
                        }
                    ],
                    [
                        'attribute' => 'exam_mark',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $reg_no = str_replace("/", "_", $model['student_number']);
                            $marksheet_id = $model['marksheet_id'];
                            $timetable_id = $model['timetable_id'];
                            $exam_mark = $model['exam_mark'];
                            return '<input placeholder="0"  name="exam_mark[]" id="' . $reg_no . '_exam_mark" type="text" class="form-control" marksheet="' . $marksheet_id . '" timetable="' . $timetable_id . '"  value="' . $exam_mark . '"/>';
                        }
                    ],
                    [
                        'attribute' => 'final_mark',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $reg_no = str_replace("/", "_", $model['student_number']);
                            $final_mark = $model['final_mark'];
                            // return "<input  id={$reg_no}_final_mark type=text name=final_mark[] class=form-control readonly/>";
                            return '<input name="final_mark[]" id="' . $reg_no . '_final_mark" type="text" class="form-control"  value="' . $final_mark . '" readonly/>';
                        }
                    ],
                    [
                        'attribute' => 'Grade',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $final_mark = $model['final_mark'];
                            $reg_no = str_replace("/", "_", $model['student_number']);
                            $grade = getGrade($reg_no, $final_mark);
                            // return "<input  id={$reg_no}_grade type=text class=form-control readonly/ >";
                            // return '<input  id="' . $reg_no . '_grade" type="text" class="form-control"  readonly"/>';
                            return '<input id="' . $reg_no . '_grade" value="' . $grade . '" type="text" class="form-control"  readonly />';
                        }
                    ],

                ],
            ]);
?>


        </div>
    </div>
</div>

<?php
$urlSaveMarks = Url::to(['/exam-management/reports/mark-grade']);
$urlGetGrade = Url::to(['/exam-management/reports/find-grade']);
$allData = json_encode($dataProvider->query->all());

$marksJs = <<< JS
let urlSaveMarks = '$urlSaveMarks';
let urlGetGrade = '$urlGetGrade';
const data =$allData
const csrfToken = $('meta[name="csrf-token"]').attr("content");

function ajaxData(data) {
    krajeeDialog.confirm('Are you sure you want to submit these marks?', function (result) {
        if (result) {            
            $.ajax({
                url: urlSaveMarks,
                type: 'POST',
                data: data,
                dataType: 'json',  
                success: function(data) {
                    console.log(data.grade)
                    // $('#app-is-loading-modal').modal('hide');
                    krajeeDialog.alert(data.message);
                },
                error: function(err) {
                    // $('#app-is-loading-modal').modal('hide');
                    krajeeDialog.alert('Updating marks failed.');
                }
            })
        }
    }); 
}

function ajaxDataGrade(data) {
    $.ajax({
        url: urlGetGrade,
        type: 'POST',
        data: data,
        dataType: 'json',  
        success: function(resp) {
            const obj = {id:data.grade_id, grade: resp.grade}
            setGradeMark(obj)
        },
        error: function(err) {
            // $('#app-is-loading-modal').modal('hide');
            krajeeDialog.alert('Updating marks failed.');
        }
    })
}

document.getElementById('save-marks-btn').addEventListener('click', e => {
    const course_work_mark_element = document.getElementsByName("course_work_mark[]")
    const exam_mark_element = document.getElementsByName("exam_mark[]")
    const course_work_mark_list = Array.from(course_work_mark_element)
    const exam_mark_list = Array.from(exam_mark_element)
    const inputValues = {}

    for (let input of course_work_mark_list) {
        let reg_no = input.getAttribute('id').replaceAll('_course_work_mark','')
        let marksheet = input.getAttribute('marksheet')
        let timetable = input.getAttribute('timetable')
        let mark = parseInt(input.value)
        const values =inputValues[reg_no]
        inputValues[reg_no] = {...values, course_work_mark:mark, marksheet_id:marksheet, timetable_id:timetable}
    }
    
    for (let input of exam_mark_list) {
        let reg_no = input.getAttribute('id').replaceAll('_exam_mark','')
        let marksheet = input.getAttribute('marksheet')
        let timetable = input.getAttribute('timetable')

        let mark = parseInt(input.value)
        const values =inputValues[reg_no]

        inputValues[reg_no] = {...values, exam_mark:mark, marksheet_id:marksheet, timetable_id:timetable}
    }
    ajaxData(inputValues)

})
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

const findExamMark = (id)  => {
    const exam = document.getElementById(id)
    if(exam.value) {
        return exam.value
    }
    return 0
}

const setFinalMark = (id, final_mark) => {
    const ele = document.getElementById(id)
    ele.value = final_mark
    if(final_mark > 100) {
        ele.classList.add('is-invalid')
        document.getElementById('save-marks-btn').disabled = true
    } else {
        if (ele.classList.contains('is-invalid')) {
            document.getElementById('save-marks-btn').disabled = false
            ele.classList.remove("is-invalid");
        }
    }
}

const setGrade = (obj) => {
    ajaxDataGrade(obj)
}


const setGradeMark = (obj) => {
    document.getElementById(obj.id).value = obj.grade

}


for(const row of data) {
    let reg_no = row.student_number.replaceAll("/","_")
    let course_work_id = reg_no + "_course_work_mark";
    let exam_mark_id = reg_no + "_exam_mark";
    let final_mark_id = reg_no + "_final_mark";
    let grade_id = reg_no + "_grade";
    const inputValues = {reg_no:reg_no,final_mark_id:final_mark_id,grade_id:grade_id}

    document.getElementById(course_work_id).addEventListener('change', e=>{
        let course_work_mark = e.target.value

        if(isNumeric(course_work_mark)) {
            let exam_mark = findExamMark(exam_mark_id)
            let final_mark = parseInt(course_work_mark) + parseInt(exam_mark) 
            setFinalMark(final_mark_id, final_mark)
            setGrade({...inputValues, final_mark:final_mark})
        }
    })
    document.getElementById(exam_mark_id).addEventListener('change', e=>{
        let exam_mark = e.target.value
        if(isNumeric(exam_mark)) {
            let course_work_mark = findExamMark(course_work_id)
            let final_mark = parseInt(course_work_mark) + parseInt(exam_mark) 
            setFinalMark(final_mark_id, final_mark)
            setGrade({...inputValues, final_mark:final_mark})
        }
    })

}

JS;
$this->registerJs($marksJs, yii\web\View::POS_READY);
