<?php

use app\models\OrgCountry;
//use app\models\Sponsor;
use kartik\date\DatePicker;
use kartik\grid\DataColumn;
use yii\grid\SerialColumn;
use app\models\Student;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
//use yii\grid\GridView;
//exit;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StudentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Students';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;

$dropDOptions = ['placeholder' => 'All', 'prompt' => 'All', 'class' => 'form-control',];
//exit;
?>
<div class="student-index bg-white">

    <h2>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Add New Student', ['create'], ['class' => 'btn btn-success float-end']) ?>
    </h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'export' => false,
        'itemLabelPlural' => 'Students', 'itemLabelSingle' => 'Student',
        'columns' => [
            ['class' => SerialColumn::class],

            ['attribute' => 'student_id', 'width' => '10px', 'class' => DataColumn::class,],
            'student_number',
            'surname',
            'other_names',
            [
                'attribute' => 'gender', 'filter' => Yii::$app->params['gender'],
                'filterInputOptions' => $dropDOptions,
            ],
            [
                'attribute' => 'nationality',
                'value' => function ($d) {
                    return $d->countryCode->nationality;
                },
                'filter' =>
                ArrayHelper::map(
                    OrgCountry::find()->innerJoinWith(['students'])->orderBy('org_country.nationality')->all(),
                    "code",
                    function ($d) {
                        return $d->nationality;
                    }
                ),
                'filterInputOptions' => $dropDOptions,
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'date_of_birth',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    //                    'pickerButton'=>false,
                    'pluginOptions' => [
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'removeButton' => false,
                        'allowClear' => true, 'format' => 'dd-M-yyyy', 'endDate' => '0d', 'autoclose' => true,
                    ],
                ],
            ],
            'id_no',
            'passport_no',
            //            [
            //                'attribute' => 'SPONSOR',
            //                'filter' => ArrayHelper::map(Sponsor::find()->all(), 'SPONSOR_ID', 'SPONSOR_NAME'),
            //                'filterInputOptions' => $dropDOptions,
            //                'value' => function ($e) {
            //                    return $e->sponsor->SPONSOR_NAME;
            //                },],
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => static function ($action, Student $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'student_id' => $model->student_id]);
                }
            ],
        ],
    ]) ?>
</div>