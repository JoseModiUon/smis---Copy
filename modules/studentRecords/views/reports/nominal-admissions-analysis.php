<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $intake integer | string */

use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$intakes = ArrayHelper::map(\app\models\Intakes::find()->all(),"intake_code",'intake_name');

$this->title = 'Admissions Analysis'. ' '. (($intake !== 0)?' : ' .$intakes[$intake]:'');
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Reports'];
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = empty($columns)?'':
    ArrayHelper::merge([['class'=>'kartik\grid\SerialColumn',],],$columns);
$url = Url::to(['/student-records/reports/nominal-admissions-analysis']);

?>


    <div class="row justify-content-md-center">
    <h5 class="col col-md-6">
        <?= Html::encode($this->title)  ?>
    </h5>
    <div class="clearfix">&nbsp;</div>
        <div class="col col-md-6">
            <?= Select2::widget([
                'name' => 'id',
                'value' => ($intake?$intake:''),
                'data' => $intakes,
                'options' => [
                    'placeholder' => 'All Intakes',
                    'multiple' => false,
                ],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [
                    "change" => "function(e) { location.href='$url?intake='+$(this).val(); }",
                ],
            ]);
            ?>
            <?= $gridColumns?GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
            ]):'' ?>

    </div>

</div>
