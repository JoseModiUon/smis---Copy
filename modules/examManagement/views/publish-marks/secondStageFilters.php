<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 1:28 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $progCode
 * @var string $progCurrId
 * @var string $level
 * @var string $year
 * @var string[] $academicLevels
 * @var string[] $semesters
 * @var string[] $groups
 */

use yii\helpers\Url;
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Filter courses
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="course-filters">
                            <div class="loader"></div>
                            <div class="error-display alert text-center" role="alert"></div>
                        </div>
                        <form action="<?=Url::to(['/exam-management/publish-marks/courses'])?>">
                            <input hidden name="code" value="<?=$progCode?>">
                            <input hidden name="curr-id" value="<?=$progCurrId?>">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="year">Year</label>
                                        <select class="custom-select form-control" id="year" name="year">
                                            <option value="">-- select --</option>
                                            <option value="2022/2023">2022/2023</option>
                                            <option value="2021/2022">2021/2022</option>
                                            <option value="2020/2021">2020/2021</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="level">Level</label>
                                        <select class="custom-select form-control" id="level" name="level">
                                            <option value="">-- select --</option>
                                            <?php foreach ($academicLevels as $level):?>
                                                <option value="<?=$level['academic_level']?>"><?=$level['academic_level_name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="semester-code">Semester</label>
                                        <select class="custom-select form-control" id="semester-code" name="semester-code">
                                            <option value="">-- select --</option>
                                            <?php foreach ($semesters as $semester):?>
                                                <option value="<?=$semester['code']?>"><?=$semester['code'] . ' - ' .$semester['description']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="group-id">Group</label>
                                        <select class="custom-select form-control" id="group-id" name="group-id">
                                            <option value="">-- select --</option>
                                            <?php foreach ($groups as $group):?>
                                                <option value="<?=$group['study_group_id']?>"><?=$group['study_group_name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="">
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

<?php
$activeFiltersUrl = Url::to(['/exam-management/publish-marks/active-course-filters']);

$coursesFiltersJs = <<< JS
const activeFiltersUrl = '$activeFiltersUrl';

const courseFiltersLoader = $('.course-filters > .loader');
courseFiltersLoader.html(loader);
courseFiltersLoader.hide();
        
const courseFiltersErrorDisplay =  $('.course-filters > .error-display');
courseFiltersErrorDisplay.hide();

getActiveFilters();

function getActiveFilters()
{
    courseFiltersErrorDisplay.hide();
    courseFiltersLoader.show();
    axios.get(activeFiltersUrl)
    .then(response => {
        if(response.data.success){
            courseFiltersLoader.hide();
            const courseFilters = response.data.courseFilters;
            $('#year').val(courseFilters.year).change();
            $('#code').val(courseFilters.code);
            $('#level').val(courseFilters.level).change();
            $('#semester-code').val(courseFilters.semesterCode).change();
            $('#group-id').val(courseFilters.groupId).change();
        }else{
            courseFiltersLoader.hide();
            courseFiltersErrorDisplay.html('Fetching active filters: ' + response.data.message) 
            courseFiltersErrorDisplay.show();
        }
    })
    .catch(error => {
        console.error(error.message);
        courseFiltersLoader.hide();
        courseFiltersErrorDisplay.html('Fetching active filters: ' + error.message) 
        courseFiltersErrorDisplay.show();
    })
}
JS;
$this->registerJs($coursesFiltersJs, yii\web\View::POS_READY);
