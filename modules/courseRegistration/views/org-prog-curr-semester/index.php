<?php

use app\models\OrgSemesterType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrSemesterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Curriculum Semesters';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-semester-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Programme Curriculum Semester',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                ?>
            </div>
        </div>
        <div class="d-flex flex-column">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'export' => false,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],

                // 'prog_curriculum_semester_id',
                [
                    'attribute' => 'progCurriculum',
                    'label' => 'Program',
                    'value' => function ($model) {
                        $desc = $model['progCurriculum']['prog_curriculum_desc'];
                        $code = $model['progCurriculum']['prog']['prog_code'];
                        return $code . ' - ' . $desc;
                    }
                ],
                [
                    'attribute' => 'acadSessionSemester',
                    'label' => 'Description',
                    'value' => 'acadSessionSemester.acad_session_semester_desc'
                ],
                [
                    'attribute' => 'semesterType',
                    'label' => 'Semester Type',
                    'value' => function ($model) {
                        if ($model['orgSemesterType']) {
                            return $model['orgSemesterType']['semester_type'];
                        }
                    }
                ],

                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update} ',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return  Html::a(' Update', ['/courseRegistration/org-prog-curr-semester/update', 'prog_curriculum_semester_id' => $model->prog_curriculum_semester_id], ['class' => ' bi bi-pencil-square  btn-link']);
                        },
                    ]

                ],
            ],
        ]); ?>

    </div>
</div>
</div>