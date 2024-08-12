<?php

use app\modules\studentRegistration\models\AcademicSession;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\studentRegistration\models\AcademicSessionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Academic Sessions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-session-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Academic Session'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'acad_session_id',
            'acad_session_name',
            'acad_session_desc',
            'start_date',
            'end_date',
            //'userid',
            //'ip_address',
            //'user_action',
            //'last_update',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, AcademicSession $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'acad_session_id' => $model->acad_session_id]);
                 }
            ],
        ],
    ]); ?>


</div>
