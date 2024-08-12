<?php
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SmNameChangeApprovalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Name Change Approval Process';
?>
<div class="sm-name-change-approval-letter">

    <div class="card" >
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="d-flex justify-content-end">
                        <?php //$approval =$name_change->getSmNameChangeApprovals()->where(['approval_status' => 'REVIEW'])->one()?>
                        <?php //echo Yii::$app->formatter->asDate($approval->approval_date, 'php:d-M-Y') ?>
                    </p>
                    <p class="d-flex justify-content-start">
                        <?php
                            $student =$name_change->getStudent()->one();
echo $student->student_number;
?>

                    </p>
                </div>
            </div>


            <p>Dear <?=$name_change->new_surname?>,</p>

            <h3 class="card-title mb-3 text-center text-decoration-underline"> RE: APPROVAL OF NAME CHANGE REQUEST</h3>
            
            <p>
                Your request to change your name from 
                <?=$name_change->current_surname?>&nbsp;&nbsp;<?=$name_change->current_othernames?>  to 
                <?=$name_change->new_surname?>&nbsp;&nbsp;<?=$name_change->new_othernames?> submitted
                on
                <?= Yii::$app->formatter->asDate($name_change->request_date, 'php:d-M-Y') ?>
                was approved.
            </p>
            Regards
        </div>
    </div>
</div>
