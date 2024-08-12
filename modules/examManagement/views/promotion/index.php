<?php

use app\models\SmExamResult;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\PromotionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Promoting Students';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-exam-result-index">
<div class="col-10 offset-1">
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12">
                <h5 class="text-center text-body-secondary fw-bold">Select the options provided below and click "Search" button to generate list</h5>
                <br>
                    <?php echo  $this->render('_search', ['model' => $searchModel]) ?>
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <br>
    <hr>
</br>
<?php if($is_searched):
?>

<h5 class="text-center text-body-secondary fw-bold">Tick the Students you would like to promote and press the button "Promote Students"!</h5>

        <?php
        $toolbar = [
            [
                'content' =>
                    Html::button('Promote students', [
                        'title' => 'Click to promote the student(s)',
                        'id' => 'promote-student-btn',
                        'class' => 'btn btn-success btn-spacer btn-sm',
                    ]),
                'options' => ['class' => 'btn-group mr-2']
            ],
            '{export}',
            // '{toggleData}',
        ];

        echo GridView::widget([
            'id' => 'promotion-grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
            'pjax' => true,
            'responsiveWrap' => false,
            'condensed' => true,
            'hover' => true,
            'striped' => false,
            'bordered' => false,
            'toolbar' => $toolbar,
            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            'export' => [
                'fontAwesome' => true,
                'label' => 'Export list'
            ],
            'panel' => [
                'heading' => '',
            ],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 50],
            'itemLabelSingle' => 'student',
            'itemLabelPlural' => 'students',
            'columns' => [
                ['class' => '\kartik\grid\SerialColumn'],
                [
                    'class' => '\kartik\grid\CheckboxColumn',
                    'checkboxOptions' => function($model, $key, $index, $widget) {
                        return [
                            'value' => $model['exam_result_id']
                        ];
                    }
                ],

                [
                    'label' => 'Registration Number',
                    'attribute' => 'fk_registration_number',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['fk_registration_number'];
                    }
                ],
                
                [
                    'label' => 'Academic Level Name',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['academic_level_name'];
                    }
                ],
                [
                    'label' => 'Semester',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['semester_code'];
                    }
                ],
               
                [
                    'label' => 'Level Result',
                    // 'attribute' => 'result',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['result'] . ' - ' .$model['ext_result'];
                    }
                ],
               
                [
                    'label' => 'Total Marks',
                    // 'attribute' => 'total_marks',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['total_marks'];
                    }
                ],
                
            ],
        ]);
        ?>
        
        <?php endif;?>
    </div>
    </div>
</div>

<?php
    
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/29/2023
 * @time: 11:41 AM
 */

$promoteStudentsUrl = Url::to(['/exam-management/promotion/promote-student',
    
    'url'=>$_GET,
    
    ]);


$grid = '#promotion-grid';
$coursesJs = <<< JS
const grid = '$grid';
const promoteStudentsUrl  = '$promoteStudentsUrl';


$(grid + '-pjax').on('click', '#promote-student-btn', function (e){
    e.preventDefault();
    let ids = getSelectedIds(grid);
    if(ids.length === 0){
        console.error('No students have been selected');
    }else{
        if(confirm('Promote Students?')){ 
           postData = {
                  'examResultID':ids
                
            }
            
            $.ajax({
                url: promoteStudentsUrl,
                type: 'POST',
                data: postData
            }).fail(function (data){
              console.error(data.responseText);
            });
        }
    }
});

// Get selected rows
function getSelectedIds(gridSelector) {
    let keys = $(gridSelector).yiiGridView('getSelectedRows');
    let ids = [];
    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');

        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    return [...new Set(ids)]
}
JS;
$this->registerJs($coursesJs, yii\web\View::POS_READY);


