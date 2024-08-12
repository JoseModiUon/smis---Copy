<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 11:42 AM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $progCode
 * @var string $progCurrId
 * @var string[] $academicLevels
 */

use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Marks publishing <i class="fa fa-angle-right" aria-hidden="true"></i> Marks publishing</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 offset-2">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Filter courses
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= Url::to(['/exam-management/publish-marks/courses']) ?>">
                            <input hidden name="code" value="<?= $progCode ?>">
                            <input hidden name="curr-id" value="<?= $progCurrId ?>">
                            <div class="form-group row">
                                <label for="year" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                                    Year
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <select class="custom-select form-control" id="year" name="year" required>
                                        <option value="">-- select --</option>
                                        <option value="2023/2024">2023/2024</option>
                                        <option value="2022/2023">2022/2023</option>
                                        <option value="2021/2022">2021/2022</option>
                                        <option value="2020/2021">2020/2021</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="level" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                                    Level
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <select class="custom-select form-control" id="level" name="level" required>
                                        <option value="">-- select --</option>
                                        <?php foreach ($academicLevels as $level) : ?>
                                            <option value="<?= $level['academic_level'] ?>"><?= $level['academic_level_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                                    <button type="submit" class="btn btn-success">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>