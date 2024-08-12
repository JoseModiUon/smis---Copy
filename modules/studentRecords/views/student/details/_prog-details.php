<?php

/** @var yii\web\View $this */

/** @var SmStudentProgrammeCurriculum $model */


use app\models\SmStudentProgrammeCurriculum;

$programme = $model->progCurriculum->prog;
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- Invoice Logo-->
                <div class="clearfix">
                    <div class="float-start">
                        <p class="m-0 h4">
                            <?= $programme->progCat->prog_cat_name ?> (<?= $programme->prog_short_name ?>)
                        </p>
                        <p><b><?= $programme->prog_full_name ?></b></p>
                    </div>
                    <div class="float-end">
                        <p><b>This is a <?=$model->progCurriculum->duration?> Year Programme</b></p>
                    </div>
                </div>

                <!--
                <div class="row row-cols-3 d-flex justify-content-between">
                    <div class="">
                        <p class="m-0 h5">
                            List Units and Semester S
                        </p>
                    </div>
                    <div class="">
                        <p class="m-0 h5">
                            List Units and Semester C
                        </p>
                    </div>
                    <div class="">
                        <p class="m-0 h5">
                            List Units and Semester B
                        </p>
                    </div>
                </div>
                -->
<!--                <div class="d-print-none mt-4">-->
<!--                    <div class="text-end">-->
<!--                        <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i>-->
<!--                            Print</a>-->
<!--                    </div>-->
<!--                </div>-->

            </div> <!-- end card-body-->
        </div> <!-- end card -->
    </div>
</div>
