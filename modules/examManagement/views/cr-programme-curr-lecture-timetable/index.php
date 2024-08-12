<?php

use app\models\CrProgCurrTimetable;
use app\models\OrgRooms;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\CrProgCurrTimetableSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Lecture Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;

if (!empty($requestData =  Yii::$app->request->get('CrProgCurrTimetableSearch'))) {
    if (array_key_exists('prog_curriculum_id', $requestData)) {
        $session->set('prog_curriculum_id', $requestData['prog_curriculum_id']);
    }
    if (array_key_exists('acad_session_id', $requestData)) {
        $session->set('acad_session_id', $requestData['acad_session_id']);
    }
}



?>
<div class="cr-prog-curr-timetable-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
            <hr>
            <div class="row mb-2">
                <div class="col-md-6">
                    <?php echo $this->render('_search', ['model' => $searchModel])
?>

                </div>
            </div>
            <hr>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
['class' => 'kartik\grid\SerialColumn'],

// 'timetable_id',
// [
//     'attribute' => 'Summary Header',
//     'width' => '310px',
//     'value' => function ($model) {
//         if ($model['progCurriculumSemGroup']) {
//             $model = $model['progCurriculumSemGroup'];
//             $progCurriculumDesc = $model['progCurriculumSemester']['progCurriculum']['prog_curriculum_desc'];
//             $academicSessionDesc = $model['progCurriculumSemester']['acadSessionSemester']['acad_session_semester_desc'];
//             $semType = $model['progCurriculumSemester']['orgSemesterType']['semester_type'];
//             //
//             $studyCentreName = $model['studyCentreGroup']['studyCentre']['study_centre_name'];
//             $studyGroupName = $model['studyCentreGroup']['studyGroup']['study_group_name'];

//             $programmeLevel = $model['programmeLevel']['programme_level_name'];
//             return $programmeLevel . ' - ' . $semType . ' - ' . $academicSessionDesc . ' - ' . $studyCentreName . ' - ' . $studyGroupName;
//         }
//     },
//     'filterType' => GridView::FILTER_SELECT2,
//     // 'filter' => ArrayHelper::map(Suppliers::find()->orderBy('company_name')->asArray()->all(), 'id', 'company_name'),
//     'filterWidgetOptions' => [
//         'pluginOptions' => ['allowClear' => true],
//     ],
//     // 'filterInputOptions' => ['placeholder' => 'Any supplier'],
//     'group' => true,  // enable grouping,
//     'groupedRow' => true,                    // move grouped column to a single grouped row
//     'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
//     'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
//     'format' => 'raw'
// ],

[
    'attribute' => 'progCurrSemGroup',
    'label' => 'Curriculum Semester Group',
    'value' => function ($model) {
        //'concat(\'PROG LEVEL: \',programme_level_name,\' - SEM TYPE: \',semester_type,\'- ACAD SESSION: \',acad_session_semester_desc,\' - STUDY CENTER: \',study_centre_name,\' - GROUP: \',study_group_name) AS desc'

        // dd($model);
        $model = $model['progCurriculumSemGroup'];
        $progCurriculumDesc = $model['progCurriculumSemester']['progCurriculum']['prog_curriculum_desc'];
        $academicSessionDesc = $model['progCurriculumSemester']['acadSessionSemester']['acad_session_semester_desc'];
        $semType = $model['progCurriculumSemester']['orgSemesterType']['semester_type'];
        //
        $studyCentreName = $model['studyCentreGroup']['studyCentre']['study_centre_name'];
        $studyGroupName = $model['studyCentreGroup']['studyGroup']['study_group_name'];

        $programmeLevel = $model['programmeLevel']['programme_level_name'];
        return $programmeLevel . ' - ' . $semType . ' - ' . $academicSessionDesc . ' - ' . $studyCentreName . ' - ' . $studyGroupName;
    },
    'group' => true
],
[
    'attribute' => 'progCurrCourse',
    'label' => 'Curriculum Course',
    'value' => function ($model) {
        $course = $model['orgProgCurrCourse']['course']['course_name'];
        $progDesc = $model['orgProgCurrCourse']['progCurriculum']['prog_curriculum_desc'];
        return $progDesc . ' - ' . $course;
    }
],
[
    'attribute' => 'exam_date',
    'value' => function ($model) {
        return Yii::$app->formatter->asDate($model->exam_date, 'php:d-M-Y');
    },
],

[
    'attribute' => 'exam_venue',
    'value' =>  function ($model) {
        // dd($model);
        return OrgRooms::find()->select('room_name')->where(['room_id' => $model['exam_venue']])->one()->room_name;
    }

],
[
    'attribute' => 'examMode',
    'value' => function ($model) {
        return $model['examMode']['exam_mode_name'];
    }
],

[
    'class' => 'kartik\grid\ActionColumn',
    'template' => '{create} | {view}',
    'contentOptions' => [
        'style' => 'white-space:nowrap;',
        'class' => 'kartik-sheet-style kv-align-middle'
    ],
    'buttons' => [
        'create' => function ($url, $model) {
            return  Html::a(' Create', ['/courseRegistration/cr-programme-curr-lecture-timetable/create', 'timetable_id' => $model->timetable_id], ['class' => ' bi bi-plus btn btn-outline-primary btn-sm']);
        },
        'view' => function ($url, $model) {
            return  Html::a(' View', ['/courseRegistration/cr-programme-curr-lecture-timetable/view', 'timetable_id' => $model->timetable_id], ['class' => ' bi bi-eye-fill btn btn-outline-primary btn-sm']);
        },
    ],
    'hAlign' => 'center',
],
                ],
            ]); ?>

        </div>
    </div>


</div>