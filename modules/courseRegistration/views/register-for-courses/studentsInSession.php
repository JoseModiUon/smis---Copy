<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/18/2023
 * @time: 10:47 AM
 */
declare(strict_types=1);

/**
 * @var $this yii\web\View
 * @var string $title
 * @var string $panelHeader
 * @var yii\data\ActiveDataProvider $studentsInSessionProvider
 * @var app\modules\courseRegistration\models\search\StudentsInSessionSearch $studentsInSessionSearch
 * @var string $marksheetId
 * @var string $progCurriculumSemesterId
 * @var string $timetableId
 * @var string $actionId
 * @var array $filters
 */

use app\helpers\SmisHelper;
use app\modules\studentRegistration\models\CourseRegistration;
use app\modules\studentRegistration\models\CourseRegistrationStatus;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
?>

    <!-- Content Header (Page header) -->
<?php
echo SmisHelper::createBreadcrumb([
    'Course registration' => Url::to(['/courseRegistration']),
    'List courses' => Url::to(['/courseRegistration/register-for-courses/timetables',
        'actionId' => $actionId,
        'year' => $filters['year'],
        'program' => $filters['progCode'],
        'level' => $filters['level'],
        'semester-id' => $filters['semesterId'],
        'group-id' => $filters['groupId']
    ]),
    'Students to register for courses' => null
]);
?>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 offset-1">
                    <div class="course-registration">
                        <div class="loader"></div>
                        <div class="error-display alert text-center" role="alert"></div>
                    </div>
                </div>
                <div class="col-10 offset-1">
                    <?php
                    $regNumberCol = [
                        'attribute' => 'regNumber',
                        'label' => 'REGISTRATION NO.',
                        'vAlign' => 'middle',
                        'value' => function($model){
                            return $model['student_number'];
                        }
                    ];
                    $surnameCol = [
                        'attribute' => 'surname',
                        'label' => 'SURNAME',
                        'vAlign' => 'middle',
                        'value' => function($model){
                            return $model['surname'];
                        }
                    ];
                    $otherNamesCol = [
                        'attribute' => 'otherNames',
                        'label' => 'OTHER NAMES',
                        'vAlign' => 'middle',
                        'value' => function($model){
                            return $model['other_names'];
                        }
                    ];
                    $examTypeCol = [
                        'label' => 'EXAM TYPE',
                        'vAlign' => 'middle',
                        'format' => 'raw',
                        'value' => function($model) use ($actionId) {
                            $name = 'student-' . $model['student_number'] . '-exam-type';
                            if($actionId === 'teaching') {
                                return '
                                <select id="' . $model['student_number'] . '" class="exam-type" name="' . $name . '">
                                    <option value=""></option>
                                    <option value="FA">FIRST ATTEMPT</option>
                                    <option value="RETAKE">RETAKE</option>
                                    <option value="SPECIAL">SPECIAL</option>
                                </select>';
                            } else {
                                return '
                            <select id="' . $model['student_number'] . '" class="exam-type" name="' . $name . '">
                                <option value="SUPP">SUPPLIMENTARY</option>
                            </select>';
                            }
                        }
                    ];
                    $statusCol = [
                        'label' => 'REGISTRATION STATUS',
                        'vAlign' => 'middle',
                        'format' => 'raw',
                        'hAlign' => 'center',
                        'value' => function($model) use($timetableId) {
                            $courseReg = CourseRegistration::find()->select(['course_reg_status_id'])->where([
                                'registration_number' => $model['student_number'],
                                'timetable_id' => $timetableId
                            ])->asArray()->one();

                            if (!empty($courseReg)) {
                                $courseRegStatus = CourseRegistrationStatus::find()->select(['course_reg_status_name'])
                                    ->where(['course_reg_status_id' => $courseReg['course_reg_status_id']])->asArray()->one();
                                $status = $courseRegStatus['course_reg_status_name'];
                                if ($status === 'CONFIRMED') {
                                    return '<div class="status-approved">CONFIRMED</div>';
                                } elseif ($status === 'PROVISIONAL') {
                                    return '<div class="status-review">PROVISIONAL</div>';
                                }
                            }

                            return '<div class="status-pending">NOT REGISTERED</div>';
                        }
                    ];

                    $gridColumns = [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'checkboxOptions' => function($model, $key, $index, $widget) {
                                return [
                                    'value' => $model['student_number']
                                ];
                            }
                        ],
                        $regNumberCol,
                        $surnameCol,
                        $otherNamesCol,
                        $examTypeCol,
                        $statusCol
                    ];

                    $toolbar = [
                        [
                            'content' =>
                                Html::button('<i class="fas fa-plus"></i> Add to course', [
                                    'title' => 'Add to course',
                                    'id' => 'register-for-course-btn',
                                    'class' => 'btn btn-success btn-spacer btn-sm',
                                ]) . '&nbsp' .
                                Html::button('<i class="fas fa-remove"></i> Remove from course', [
                                    'title' => 'Remove from course',
                                    'id' => 'drop-courses-btn',
                                    'class' => 'btn btn-danger btn-spacer btn-sm',
                                ]),
                            'options' => ['class' => 'btn-group mr-2']
                        ],
                    ];

                    try{
                        echo GridView::widget([
                            'id' => 'register-for-courses-grid',
                            'dataProvider' => $studentsInSessionProvider,
                            'filterModel' => $studentsInSessionSearch,
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
                            'export' => false,
                            'panel' => [
                                'heading' => $panelHeader
                            ],
                            'persistResize' => false,
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
                </div>
            </div>
        </div>
    </section>

<?php
$registerForCoursesUrl = Url::to(['/courseRegistration/register-for-courses/add-to-course']);
$getSelectedExamTypesUrl = Url::to(['/courseRegistration/register-for-courses/selected-exam-types']);
$dropCoursesUrl = Url::to(['/courseRegistration/register-for-courses/remove-from-course']);
$getSelectedExamTypesUrl = Url::to(['/courseRegistration/register-for-courses/selected-exam-types']);

$registerForCoursesJs = <<< JS
const registerForCoursesUrl = '$registerForCoursesUrl';
const getSelectedExamTypesUrl = '$getSelectedExamTypesUrl';
const dropCoursesUrl = '$dropCoursesUrl';
const marksheetId = '$marksheetId';
const timetableId = '$timetableId';
const progCurriculumSemesterId = '$progCurriculumSemesterId';
const actionId = '$actionId';

const courseRegistrationLoader = $('.course-registration > .loader');
courseRegistrationLoader.html(loader);
courseRegistrationLoader.hide();
        
const courseRegistrationErrorDisplay =  $('.course-registration > .error-display');
courseRegistrationErrorDisplay.hide();

if(actionId === 'teaching') {
    $('.exam-type').val('FA').change();
} else{
    $('.exam-type').val('SUPP').change();
}

/**
* Add students in course
*/
$('#register-for-courses-grid-pjax').on('click', '#register-for-course-btn', function (e){
    e.preventDefault();
    if(getSelectedIds('#register-for-courses-grid').length === 0){
        alert('No students have been selected.');
    }else{
        let students = [];
        let missingExamType = false;
        $('table > tbody').find('tr.table-danger').each(function (e){
            let examTypeInput = $(this).find('.exam-type');
            let name = examTypeInput.attr('name');
            let regNumber = name.split('-')[1];
            if(examTypeInput.val() === ''){
                missingExamType = true;
                return;
            }else{
                let student = {  
                    'regNumber' : regNumber,
                    'examType' : examTypeInput.val()
                };
                students.push(student);
            }
        });
        
         if(missingExamType){
             alert('Select the exam type for all students you want to register for.');
         }else{
            if(confirm('Register the selected students in the course?')){
                courseRegistrationErrorDisplay.hide();
                courseRegistrationLoader.show();
                $.ajax({
                    url: registerForCoursesUrl,
                    type: 'POST',
                    data: {
                        'marksheetId': marksheetId, 
                        'progCurriculumSemesterId': progCurriculumSemesterId,
                        'students' : students
                    }
                }).done(function (data){
                    courseRegistrationLoader.hide();
                     if(!data.success){
                        courseRegistrationErrorDisplay.html(data.message) 
                        courseRegistrationErrorDisplay.show();
                     }
                }).fail(function (data){
                     courseRegistrationLoader.hide();
                     courseRegistrationErrorDisplay.html(data.responseText) 
                     courseRegistrationErrorDisplay.show();
                });
            }
         }
    }
});

/**
* Get the exam types for the courses registered for and set their select field values.
*/
function getSelectedExamType(){
    let students = [];
    
    $('table > tbody').find('tr .exam-type').each(function (e){
        students.push($(this).attr('id'));
    });
    
    axios.get(getSelectedExamTypesUrl, {
        params: {
            timetableId: timetableId,
            students: students
        }
    })
    .then(response => {
        let regTypes = response.data.regTypes;
        if(regTypes.length > 0) {
            regTypes.forEach(function (regType) {
                document.getElementById(regType.regNumber).value = regType.code;
            });
        }
    })
    .catch(error => {
        console.log(error);
        courseRegistrationLoader.hide();
        courseRegistrationErrorDisplay.html('Fetching selected exam types: ' + error.message) 
        courseRegistrationErrorDisplay.show();
    });
}
getSelectedExamType();

/**
* Remove students from course
*/
$('#register-for-courses-grid-pjax').on('click', '#drop-courses-btn', function (e){
    e.preventDefault();
    let students = getSelectedIds('#register-for-courses-grid');
    console.log(students);
    if(students.length === 0){
        alert('No students have been selected.');
    }else{
        if(confirm('Remove the selected students from this course?')){
            courseRegistrationErrorDisplay.hide();
            courseRegistrationLoader.show(); 
            $.ajax({
                url: dropCoursesUrl,
                type: 'POST',
                data: {
                    'marksheetId': marksheetId, 
                    'students' : students
                }
            }).done(function (data){
                courseRegistrationLoader.hide();
                if(!data.success){
                    courseRegistrationErrorDisplay.html(data.message);
                    courseRegistrationErrorDisplay.show();
                }
            }).fail(function (data){
                courseRegistrationLoader.hide();
                courseRegistrationErrorDisplay.html(data.responseText);
                courseRegistrationErrorDisplay.show();
            });
        }
    }
});

// Get selected rows
function getSelectedIds(gridSelector) {
    let keys = $(gridSelector).yiiGridView('getSelectedRows');
    let ids = [];
    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');

        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    return [...new Set(ids)]
}
JS;
$this->registerJs($registerForCoursesJs, yii\web\View::POS_READY);
