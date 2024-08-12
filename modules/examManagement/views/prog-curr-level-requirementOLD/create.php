<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */

$this->title = 'Create Level Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/examinationManagement']];
$this->params['breadcrumbs'][] = ['label' => 'Level Requirements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$csrf = \Yii::$app->request->csrfToken;
?>
<div class="org-course-prerequisite-create">

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <h2>Create Level Requirements</h2>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <form id="update-profile-form" method="post" action="<?=Url::to(['/examinationManagement/prog-curr-level-requirement/save'])?>">

                    <input type="hidden" id="min-courses-taken" name="_csrf" value="<?=$csrf?>">

                    <input type="hidden" id="prog-curr-id" name="prog-curr-id" value="<?=Yii::$app->getRequest()->getQueryParam('prog_curriculum_id')?>">


                    <div class="form-group row">
                        <label for="study-level" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Study level
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select class="custom-select form-control" id="study-level" name="study-level" required>
                                <option value="">-- select --</option>
                                <option value="1">First Year</option>
                                <option value="2">Second Year</option>
                                <option value="3">Third Year</option>
                                <option value="4">Fourth Year</option>
                                <option value="5">Fifth Year</option>
                                <option value="6">Sixth Year</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Min Courses taken
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="min-courses-taken" name="min-courses-taken" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="pass-type" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Pass Type
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select class="custom-select form-control" id="pass-type" name="pass-type" required>
                                <option value="">-- select --</option>
                                <option value="ALL">All</option>
                                <option value="BEST">Best</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Min Courses to Pass
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="min-courses-pass" name="min-courses-pass" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="pass-type" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Choices for GPA Courses
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select class="custom-select form-control" id="gpa-choices" name="gpa-choices" required>
                                <option value="">-- select --</option>
                                <option value="ALL">All</option>
                                <option value="BEST">Best</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            No of GPA Courses
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="gpa-num" name="gpa-num" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Final GPA Weight
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="number" step="1" class="form-control" id="gpa-weight" name="gpa-weight" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Pass Result
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="pass-result" name="pass-result" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Pass Recommendation
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="pass-recom" name="pass-recom" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Incomplete Result
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="incomplete-res" name="incomplete-res" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Incomplete Recommendation
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="incomplete-recom" name="incomplete-recom" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Fail Result
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="fail-res" name="fail-res" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Fail Recommendation
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="fail-recom" name="fail-recom" value="">
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
