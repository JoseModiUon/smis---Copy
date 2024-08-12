<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'Student Nationality Stats';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Reports'];
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = empty($columns)?'':
    ArrayHelper::merge([['class'=>'kartik\grid\SerialColumn',],],$columns);

?>

<div class="">

    <h5>
        <?= Html::encode($this->title)  ?>
    </h5>
    <div class="clearfix">&nbsp;</div>

    <div class="row justify-content-md-center">
        <div class="col col-md-6">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
            ]) ?>

        </div>
    </div>

</div>
