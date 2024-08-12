<?php

use app\modules\feesManagement\models\ProgrammeCurriculum;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii2ajaxcrud\ajaxcrud\BulkButtonWidget;
use yii2ajaxcrud\ajaxcrud\CrudAsset;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\search\ProgrammeCurriculumSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Programme Curriculum Billing';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fees Management'), 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);
?>
<div class="programme-curriculum-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?php Html::a('Create Programme Curriculum', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
<div id="ajaxCrudDatatable">
    <?= GridView::widget([
        'id' => 'crud-datatable',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'prog_code',
                'label' => 'Programme Code',
                'value' => function ($model) {
                    return $model->orgProgrammes->prog_code;
                }
            ],
            [
                'attribute' => 'prog_curriculum_desc',
                'label' => 'Programme Name',
            ],
            [
                'attribute' => 'billing_type_desc',
                'label' => 'Billing Type',
                'value' => function ($model) {
                    return $model->billingType->billing_type_desc;
                }
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}',
                'contentOptions' => [
                    'style' => 'white-space:nowrap',
                    'class' => 'kartik-sheet-style kv-align-middle'
                ],
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(
                            ' Update Billing',
                            [
                                '/fees-management/programme-curriculum/update',
                                'prog_curriculum_id' => $model->prog_curriculum_id,
                            ],
                            [
                                'class' => ' bi btn-link bi bi-pencil-square text-success',
                            ],
                        );
                    },
                ]
            ],
        ],
    ]); ?>
    </div>


</div>


<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "", // always need it for jquery plugin
    "clientOptions" => [
        "tabindex" => true,
        // "backdrop" => "static",
        "keyboard" => true,
    ],
    "options" => [
        "tabindex" => true
    ]
]) ?>
<?php Modal::end(); ?>