<?php

use app\models\CrProgCurrTimetable;
use app\models\CrProgrammeCurrLectureTimetable;
use app\models\OrgCourses;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrProgrammeCurrLectureTimetable $model */

$params = Yii::$app->request->get();

$course = OrgCourses::find()->select('course_name')->where(['org_courses.course_id' => $params['course_id']])->one();
$this->title = 'Update Lecture Timetable: ' . $course['course_name'];
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = ['label' => 'Lecture Timetables', 'url' => ['/courseRegistration/cr-programme-curr-lecture-timetable/view', 'timetable_id' => $params['timetable_id'], 'course_id' => $params['course_id']]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="card">
    <div class="card-body">
            <div class="mb-2 mt-2">
                <button class="bi bi-arrow-counterclockwise btn btn-outline-primary" onclick="history.back()"> Go Back</button>
            </div>
        <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

        <?php echo $this->render('info_card', ['params' => $params]) ?>


        <hr>

        <?= $this->render('_form_update', [
            'params' => $params,
            'lec_model' => $model,
            'model' => CrProgCurrTimetable::findOne($params['timetable_id'])
        ]) ?>

    </div>
</div>

</div>