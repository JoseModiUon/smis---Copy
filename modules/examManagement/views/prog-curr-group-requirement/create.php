<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */

$this->title = 'Create Course Group Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Course Group Requirements', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;

$csrf = \Yii::$app->request->csrfToken;
?>
<<<<<<< HEAD
<<<<<<< HEAD
<div class="org-course-prerequisite-create">

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <h2>Create Course Group Requirements</h2>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <form id="update-profile-form" method="post" action="<?=Url::to(['/exam-management/prog-curr-group-requirement/save'])?>">

                    <input type="hidden" id="min-courses-taken" name="_csrf" value="<?=$csrf?>">

                    <input type="hidden" id="prog-curr-level-req-id" name="prog-curr-level-req-id" value="<?=Yii::$app->getRequest()->getQueryParam('prog_curr_level_req_id')?>">


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Course Group Name
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select name="course-group" id="course-group" class="form-control">
                                <option value="">Select Course Grouping</option>
                                <?php foreach ($dropdown as $dropdowns): ?>
                                    <option value="<?= $dropdowns->course_group_id ?>"><?= $dropdowns->course_group_name ?></option>
                                    <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Minimum Group Courses
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="min-courses" name="min-courses" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Group Pass Type
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="pass-type" name="pass-type" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Min Group Pass
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="group-pass" name="group-pass" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        GPA Pass Type
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="gpa-pass" name="gpa-pass" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        GPA Courses
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="gpa-courses" name="gpa-courses" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Extra Courses Processing
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" maxlength="4" step="1" class="form-control" id="extra-courses" name="extra-courses" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                            <button type="submit" id="btn-update-profile" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

</div>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
<div class="org-course-prerequisite-create">

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <h2>Create Course Group Requirements</h2>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <form id="update-profile-form" method="post" action="<?=Url::to(['/exam-management/prog-curr-group-requirement/save'])?>">

                    <input type="hidden" id="min-courses-taken" name="_csrf" value="<?=$csrf?>">

                    <input type="hidden" id="prog-curr-level-req-id" name="prog-curr-level-req-id" value="<?=Yii::$app->getRequest()->getQueryParam('prog_curr_level_req_id')?>">


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Course Group Name
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select name="course-group" id="course-group" class="form-control">
                                <option value="">Select Course Grouping</option>
                                <?php foreach ($dropdown as $dropdowns): ?>
                                    <option value="<?= $dropdowns->course_group_id ?>"><?= $dropdowns->course_group_name ?></option>
                                    <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Minimum Group Courses
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="min-courses" name="min-courses" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Group Pass Type
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="pass-type" name="pass-type" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Min Group Pass
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="group-pass" name="group-pass" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        GPA Pass Type
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="gpa-pass" name="gpa-pass" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        GPA Courses
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="gpa-courses" name="gpa-courses" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Extra Courses Processing
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" maxlength="4" step="1" class="form-control" id="extra-courses" name="extra-courses" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                            <button type="submit" id="btn-update-profile" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

</div>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
