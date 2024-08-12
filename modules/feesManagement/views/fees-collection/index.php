<?php

use app\modules\feesManagement\models\StudentProgrammeCurriculum;
use app\modules\feesManagement\models\ProgrammeCurriculum;
use kartik\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii2ajaxcrud\ajaxcrud\BulkButtonWidget;
use yii2ajaxcrud\ajaxcrud\CrudAsset;
use yii\bootstrap5\Modal;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\search\ProgrammeCurriculumSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$curriculum_id = $searchModel->prog_curriculum_id;
$session_name = $searchModel->acad_session_name;
$start_date = $searchModel->start_date;
$end_date = $searchModel->end_date;
$this->title = 'Fees Collection';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fees Management'), 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);


$rows = Yii::$app->db->createCommand(
    "
    SELECT sspc.registration_number AS reg_number, 
           CONCAT(sas.other_names, ' ', sas.surname) AS student_names, 
           ffp.entry_date AS date_paid, 
           oas.acad_session_name AS academic_year,
           ffp.trans_amount AS total_amount, 
           opc.prog_curriculum_desc,
           opc.prog_curriculum_id, 
           ffp.fee_paymt_id,
           ffp.student_prog_curriculum_id
    FROM smis.fss_fee_payments ffp
    INNER JOIN smis.sm_student_programme_curriculum sspc 
        ON ffp.student_prog_curriculum_id = sspc.student_prog_curriculum_id 
    INNER JOIN smis.org_programme_curriculum opc 
        ON sspc.prog_curriculum_id = opc.prog_curriculum_id 
    INNER JOIN smis.sm_admitted_student sas 
        ON sspc.adm_refno = sas.adm_refno 
    INNER JOIN smis.sm_academic_progress sap
        ON sspc.student_prog_curriculum_id = sap.student_prog_curriculum_id  
    INNER JOIN smis.org_academic_session oas
        ON sap.acad_session_id = oas.acad_session_id         
    WHERE opc.prog_curriculum_id = :curriculum_id
    AND ffp.receipt_status != 'CANCELLED'
    AND oas.acad_session_name = :session_name
    AND ffp.entry_date BETWEEN :start AND :end
    GROUP BY opc.prog_curriculum_desc, sspc.registration_number, student_names, date_paid, academic_year, total_amount, fee_paymt_id, opc.prog_curriculum_id
    "
)
    ->bindValue(':curriculum_id', $curriculum_id)
    ->bindValue(':session_name', $session_name)
    ->bindValue(':start', $start_date)
    ->bindValue(':end', $end_date)
    ->queryAll();







$data = new ArrayDataProvider([
    'allModels' => $rows,
    'pagination' => [
        'pageSize' => 10,
    ],
]);


$total = 0;

foreach ($data->models as $model) {
    $total += $model['total_amount'];
}

Yii::$app->params['total'] = $total;

?>
<div class="programme-curriculum-index">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
    </div>

    <p>
        <?php Html::a('Create Programme Curriculum', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div id="ajaxCrudDatatable">
        
        <?php echo $this->render('_search', ['model' => $searchModel]);
        ?>
        
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $data,
                            'responsive' => true,
                            'showPageSummary' => true,
                            'pjax' => true,
                            'columns' => [
                                [
                                    'class' => 'kartik\grid\SerialColumn',
                                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                                    'width' => '36px',
                                    'pageSummary' => 'Total',
                                    'pageSummaryOptions' => ['colspan' => 4],
                                    'header' => '',
                                    'headerOptions' => ['class' => 'kartik-sheet-style']
                                ],
                                // 'student_prog_curriculum_id',
                                'reg_number',
                                'student_names',
                                'date_paid',
                                [
                                    'attribute' => 'total_amount',
                                    'format' => ['decimal', 2],
                                    'pageSummary' => true
                                ],
                                [
                                    'attribute' => 'total_amount',
                                    'label' => 'Test',
                                    'visible' => false,
                                    'value' => function ($model, $key, $index, $widget) use ($total) {
                                        return $total;
                                    },
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
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