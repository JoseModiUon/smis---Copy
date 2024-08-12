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

$this->title = Yii::t('app', ' Detailed Student Academic Progress');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exam Management'), 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
$details = new StudentProgrammeCurriculum();
$fullName = $details->getFullName($registrationNumber);
$status = $details->getStatus($registrationNumber);
$status_id = $details->getStatusId($registrationNumber);
$module = $details->getModule($registrationNumber);
$semesterProgress = $details->getStudentAcademicProgressDetailed($registrationNumber);

$url = Url::to(['/student-records/student-semester-progress/detailed']);
$curr = Url::to([
    '/student-records/student-semester-progress/index',
    'StudentSemesterProgressSearch[registration_number]' => $registrationNumber
], true);



$data = new ArrayDataProvider([
    'allModels' => $semesterProgress,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
// dd($data);
?>
<div class="student-programme-curriculum-index">
    <?php Pjax::begin(); ?>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <?php
            if ($registrationNumber == null && $fullName == false) {
            ?>
            <?php
                echo $this->render('_search1', ['model' => $searchModel]);
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
                            'dataProvider' => $data,
                            // 'filterModel' => $searchModel,
                            'responsive' => true,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'attribute' => 'acad_session_name',
                                    'label' => 'Academic Session',
                                ],
                                [
                                    'attribute' => 'academic_level',
                                    'label' => 'Level'
                                ],
                                [
                                    'attribute' => 'sem_progress_number',
                                    'label' => 'Progress'
                                ],
                                'semster_name',
                                [
                                    'attribute' => 'progress_status_name',
                                    'label' => 'Status',
                                ],
                                'registration_date',
                                'status_name',
                                [
                                    'attribute' => 'study_centre_name',
                                    'label' => 'Study Center'
                                ],
                                [
                                    'attribute' => 'study_group_name',
                                    'label' => 'Study Group'
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
                                'before' => '<h5>* ' . Html::encode($this->title) . '</h5>',
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