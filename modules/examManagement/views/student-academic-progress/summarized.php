<?php

use app\models\AdmittedStudent;
use app\models\OrgProgrammeCurriculum;
use app\models\Student;
use app\models\StudentProgrammeCurriculum;
use yii\helpers\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\AcademicProgressSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Student Academic Progress (Summarized)');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exam Management'), 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
$request = Yii::$app->request;
$registration_number = $request->get('registration_number');
$check = $registration_number != null;
if($check){
$studProg = StudentProgrammeCurriculum::findOne(['registration_number' => $registration_number]);
$student = AdmittedStudent::findOne(['adm_refno' => $studProg->adm_refno]);
$curriculum = OrgProgrammeCurriculum::findOne(['prog_curriculum_id' => $studProg->prog_curriculum_id]);
}


?>
<div class="student-academic-progress-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_search'); ?>
    <?php if($check){?>
    <p class="text-bold">FULL NAME: <?=$student->surname?> <?=$student->other_names?></p>
<p class="text-bold">REGISTRATION NUMBER: <?=$studProg->registration_number?></p>
<p class="text-bold">PROGRAMME : <?=$curriculum->prog_curriculum_desc?></p>
<?php }?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'acad_session_name',
                'label' => 'ACADEMIC SESSION',
            ],
            [
                'attribute' => 'academic_level_name',
                'label' => 'ACADEMIC LEVEL',
            ],
            [
                'attribute' => 'status',
                'label' => 'STATUS',
            ],
        ],
    ]); ?>


</div>
