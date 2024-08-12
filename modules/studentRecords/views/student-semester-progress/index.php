<?php

use app\modules\feesManagement\models\StudentProgrammeCurriculum;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii2ajaxcrud\ajaxcrud\CrudAsset;
use yii\data\ArrayDataProvider;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\StudentSemesterProgressSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$request = Yii::$app->request;

$params = $request->bodyParams;
$registrationNumber = $request->get('StudentSemesterProgressSearch')['registration_number'] ?? null;

$this->title = Yii::t('app', ' Student Academic Progress (Summarized)');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exam Management'), 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
$details = new StudentProgrammeCurriculum();
$fullName = $details->getFullName($registrationNumber);
$status = $details->getStatus($registrationNumber);
$status_id = $details->getStatusId($registrationNumber);
$module = $details->getModule($registrationNumber);
// $semesterProgress = $details->getSemesterProgressGrid($registrationNumber);
$studProgCurrId = $semesterProgress[0]['student_prog_curriculum_id'] ?? null;

$academicProgressId = $semesterProgress[0]['academic_progress_id'] ?? null;


$url = Url::to(['/student-records/student-semester-progress/']);
$curr = Url::to([
    '/student-records/student-semester-progress/index',
    'StudentSemesterProgressSearch[registration_number]' => $registrationNumber
], true);


// $data = new ArrayDataProvider([
//     'allModels' => $semesterProgress,
//     'key' => 'student_semester_session_id',
//     'pagination' => [
//         'pageSize' => 10,
//     ],
// ]);
?>
<div class="student-programme-curriculum-index">
    <?php Pjax::begin(); ?>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <?php
            if ($registrationNumber == null && $fullName == false) {
            ?>
            <?php
                echo $this->render('_search', ['model' => $searchModel]);
            } else {
            ?>
                <div class="d-flex justify-content-between">
                    <a href="<?= $url ?>" class="btn btn-primary mb-3"><i class="fas fa-chevron-left"></i> Back</a>
                </div>
            <?php
            }
            ?>
            <?php
            if ($registrationNumber != null && $fullName != false) {
            ?>
                <div class="card">
                    <div class="card-body">
                        <p>
                            <?php Html::a(Yii::t('app', 'Create Student Programme Curriculum'), ['create'], ['class' => 'btn btn-success']) ?>
                        </p>
                        <table class="table table-sm">

                            <tbody>
                                <tr>
                                    <td>
                                        <p class="text-bold">REGISTRATION NUMBER</p>
                                    </td>
                                    <td>
                                        <p class="text-bold"><?= $registrationNumber ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-bold">FULL NAME</p>
                                    </td>
                                    <td>
                                        <p class="text-bold"><?= $fullName ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-bold">PROGRAMME</p>
                                    </td>
                                    <td>
                                        <p class="text-bold"><?= $module ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            // 'filterModel' => $searchModel,
                            'responsive' => true,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'acad_session_name',
                                    'label' => 'Academic Session',
                                    'value' => function ($model) {
                                        return $model->academicProgress->academicSession->acad_session_name;
                                    }
                                ],
                                [
                                    'attribute' => 'semster_name',
                                    'label' => 'Semester Name',
                                    'value' => function ($model) {
                                        return $model->academicProgress->studentSemSessionProgress->academicSessionSemester->semesterCode->semster_name;
                                    }
                                ],
                                [
                                    'attribute' => 'sem_progress_number',
                                    'label' => 'Semester Progress Number',
                                    'value' => function($model){
                                        return $model->academicProgress->studentSemSessionProgress->sem_progress_number;
                                    }
                                ],
                                [
                                    'attribute' => 'status_name',
                                    'label' => 'Remarks/ Status',
                                    'value' => function($model){
                                        return $model->academicProgress->studentSemSessionProgress->studentSemesterSessionStatus->status_name;
                                    }
                                ],
                            ],
                            'toolbar' => [
                                [
                                    'content' =>
                                    '{toggleData}' .
                                        '{export}'
                                ],
                            ],
                            'panel' => [
                                'type' => 'default',
                                'before' => '<h5 class="text-bold"> ' . Html::encode($this->title) . '</h5>',
                                '<div class="clearfix"></div>',
                            ]
                        ]); ?>

                    </div>
                </div>

        </div>
    </div>
<?php
            } else if ($registrationNumber != null && isset($_GET['StudentSemesterProgressSearch'])) {
?>


    <div class="card text-white bg-warning">
        <img class="card-img-top" src="holder.js/100px180/" alt="">
        <div class="card-body">
            <h4 class="card-title">Registration number does not exist</h4>
            <p class="card-text">Click on refresh and search for another record</p>
        </div>
    </div>

<?php
            }
?>
<?php Pjax::end(); ?>
</div>
