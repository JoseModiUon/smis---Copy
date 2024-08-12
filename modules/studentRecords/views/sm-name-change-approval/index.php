<?php

use app\models\SmNameChange;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SmNameChangeApprovalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Name Change Approval Process';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Name Change Requests Report', 'url' => ['/student-records/sm-name-change/reports']];
$name_change_id = Yii::$app->request->get('name_change_id');
$student = SmNameChange::findOne($name_change_id)->getStudent()->one();
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="sm-name-change-approval-index">

    <div class="card" >
        <div class="card-body">

            <h3 class="card-title mb-3"><?= Html::encode($this->title.' - '. $student->surname .' '. $student->other_names . ' - '. $student->student_number) ?></h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    // 'name_change_approval_id',
                    // 'name_change_id',
                    'approval_status',
                    'remarks',
                    // 'approved_by',
                    [
                        'attribute' => 'approval_date',
                        // 'contentOptions' => [ 'style' => 'width: 15%;' ],
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDate($model->approval_date, 'php:d-M-Y');
                        },
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
