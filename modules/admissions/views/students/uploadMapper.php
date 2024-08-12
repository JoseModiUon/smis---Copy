<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/13/2024
 * @time: 7:29 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var array $studyCenters
 * @var array $studyGroups
 * @var array $studentCategories
 * @var array $intakes
 * @var array $intakeSources
 * @var array $sponsors
 * @var array $semesters
 */

use app\helpers\SmisHelper;
use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<?php
echo SmisHelper::createBreadcrumb([
    'Admissions' => Url::to(['/admissions']),
    'Upload existing students' => Url::to(['students/upload-existing']),
    'Mapper' => null
]);
?>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-lg-8 offset-md-2 offset-lg-2">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Global semesters
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>semester_id</th>
                                        <th>description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($semesters as $semester): ?>
                                        <tr>
                                            <td><?= $semester['acad_session_semester_id'] ?></td>
                                            <td>
                                                <?= 'Semester ' . $semester['semester_code'] . ' - ' . $semester['acad_session_name'] ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Study centers
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>study_centre_id</th>
                                        <th>study_center_name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($studyCenters as $studyCenter): ?>
                                        <tr>
                                            <td><?= $studyCenter['study_centre_id'] ?></td>
                                            <td><?= $studyCenter['study_centre_name'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Study groups
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>study_group_id</th>
                                        <th>study_group_name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($studyGroups as $studyGroup): ?>
                                        <tr>
                                            <td><?= $studyGroup['study_group_id'] ?></td>
                                            <td><?= $studyGroup['study_group_name'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Student categories
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>student_category_id</th>
                                        <th>student_category_name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($studentCategories as $studentCategory): ?>
                                        <tr>
                                            <td><?= $studentCategory['std_category_id'] ?></td>
                                            <td><?= $studentCategory['std_category_name'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Intakes
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>intake_code</th>
                                        <th>intake_name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($intakes as $intake): ?>
                                        <tr>
                                            <td><?= $intake['intake_code'] ?></td>
                                            <td><?= $intake['intake_name'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Intake sources
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>source_id</th>
                                        <th>source</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($intakeSources as $intakeSource): ?>
                                        <tr>
                                            <td><?= $intakeSource['source_id'] ?></td>
                                            <td><?= $intakeSource['source'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Sponsors
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>sponsor_id</th>
                                        <th>sponsor_name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($sponsors as $sponsor): ?>
                                        <tr>
                                            <td><?= $sponsor['sponsor_id'] ?></td>
                                            <td><?= $sponsor['sponsor_name'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>