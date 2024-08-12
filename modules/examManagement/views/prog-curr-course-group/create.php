<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */

$this->title = 'Create Course Group';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Course Group', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$csrf = \Yii::$app->request->csrfToken;
?>
<<<<<<< HEAD
<<<<<<< HEAD
<div class="org-course-prerequisite-create">

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <h2>Create Course Group</h2>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <form id="update-profile-form" method="post" action="<?=Url::to(['/exam-management/prog-curr-course-group/save'])?>">

                    <input type="hidden" id="min-courses-taken" name="_csrf" value="<?=$csrf?>">


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Course Group Name
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="group-name" name="group-name" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Course Group Desc
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="group-desc" name="group-desc" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Course Group Type
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="group-type" name="group-type" value="">
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
        <h2>Create Course Group</h2>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <form id="update-profile-form" method="post" action="<?=Url::to(['/exam-management/prog-curr-course-group/save'])?>">

                    <input type="hidden" id="min-courses-taken" name="_csrf" value="<?=$csrf?>">


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Course Group Name
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="group-name" name="group-name" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Course Group Desc
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="group-desc" name="group-desc" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                        Course Group Type
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" step="1" class="form-control" id="group-type" name="group-type" value="">
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
