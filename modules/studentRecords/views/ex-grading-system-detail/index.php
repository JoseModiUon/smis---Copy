<?php

use app\models\ExGradingSystemDetail;
use app\models\portal_student\ExGradingSystem;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\ExGradingSystemDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$grading_system_id = Yii::$app->request->queryParams['grading_system_id'];

Yii::$app->session->set('grading_system_id', $grading_system_id);
$grading_system = ExGradingSystem::findOne($grading_system_id);
$this->title = 'Grading System Details : ' . $grading_system->grading_system_desc;
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = 'Grading System Details';


?>
<div class="ex-grading-system-detail-index">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Grading System Detail',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                ?>

            </div>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    // 'grading_system_id',
                    'lower_bound',
                    'upper_bound',
                    'grade',
                    //'grade_point',
                    //'is_active',
                    //'legend',
                    //'extlegend',
                    //'recomm_id',
                    //'userid',
                    //'ip_address',
                    //'user_action',
                    //'last_update',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/student-records/ex-grading-system-detail/update', 'grading_system_detail_id' => $model->grading_system_detail_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>