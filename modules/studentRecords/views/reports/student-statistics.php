<?php
/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-02-28 19:10:37 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-03-05 10:56:19
 * @Description: file:///home/wahomez/Github/smis/modules/studentRecords/views/reports/student-statistics.php
 */

use app\helpers\SmisHelper;
use app\models\OrgAcademicSession;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\web\ServerErrorHttpException;

?>

<?php

$this->title = $title;

echo SmisHelper::createBreadcrumb([
    'Student Records' => Url::to(['/student-records']),
    'Reports' => Url::to(['']),
    $title => null

]);
?>


<div class="section">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h6 class="text-center fw-bold text-body-secondary">Select Academic Session</h6>

                <?php echo  $this->render('_search-student-stats', ['model' => $model]) ?>
                <hr>

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <?php foreach ($units as $unit) : ?>
                    <br></br>

                    <div class="alert alert-secondary" role="alert">
                        <h6 class="fw-bold text-center"><?= Html::encode($unit['unit_name']) ?></h6>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2">Programmes</th>
                                <?php foreach ($levels as $level) : ?>
                                    <?php if ($level['academic_level'] == '1') {
                                        echo '<th colspan="2" >First Year</th>';
                                    } elseif ($level['academic_level'] == '2') {
                                        echo '<th colspan="2">Second Year</th>';
                                    } ?>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <?php foreach ($levels as $level) : ?>
                                    <th>M</th>
                                    <th>F</th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($programmes as $programme) : ?>
                                <?php if ($programme['prog_short_name'] == $unit['prog_short_name']) { ?>
                                    <tr>

                                        <td><?= Html::encode($programme['prog_short_name']) ?></td>

                                        <?php foreach ($levels as $level) : ?>
                                            <td>
                                                <?php
                                                $maleCount = 0;
                                                foreach ($maleStudent as $student) {
                                                    if ($student['prog_short_name'] == $programme['prog_short_name'] && $student['academic_level'] == $level['academic_level']) {
                                                        $maleCount = $student['total_students'];
                                                        break;
                                                    }
                                                }
                                                echo Html::encode($maleCount);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $femaleCount = 0;
                                                foreach ($femaleStudent as $student) {
                                                    if ($student['prog_short_name'] == $programme['prog_short_name'] && $student['academic_level'] == $level['academic_level']) {
                                                        $femaleCount = $student['total_students'];
                                                        break;
                                                    }
                                                }
                                                echo Html::encode($femaleCount);
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php } ?>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                                            </br>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>