<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/16/2023
 * @time: 12:02 PM
 */

/**
 * @var string[] $years
 * @var string[] $programs
 * @var array $programsCurr
 * @var string[] $academicLevels
 * @var string[] $semesters
 * @var string[] $groups
 * @var string $action
 * @var string $actionId
 */

use yii\helpers\Url;

?>
    <div class="row">
        <div class="col-10 offset-1">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Filter courses instructions | <?= ($actionId === 'teaching') ? 'Teaching ' : 'Supplementary ' ?> timetable
                    </h3>
                </div>
                <div class="card-body">
                    <div class="course-filters">
                        <div class="loader"></div>
                        <div class="error-display alert text-center" role="alert"></div>
                    </div>
                    <ul>
                        <li>All fields marked with <span class="required-control-label"></span> are <span
                                    class="text-danger"> MANDATORY </span></li>
                        <li>First select the following filters:
                            <ul>
                                <li>Academic Year</li>
                                <li>Program</li>
                                <li>Year of Study</li>
                                <li>Group</li>
                            </ul>
                        </li>
                        <li>Click on the APPLY button to fetch semesters that match these filters</li>
                        <li>Select the Semester</li>
                        <li>Finally click the APPLY button again to fetch timetables that match these filters</li>
                    </ul>
                    <form action="<?= $action ?>">
                        <input type="hidden" name="actionId" value="<?= $actionId ?>">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="year" class="required-control-label">Academic year</label>
                                    <select class="custom-select form-control" id="year" name="year">
                                        <option value="">-- select --</option>
                                        <?php foreach ($years as $key => $value): ?>
                                            <option value="<?= $value ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="program" class="required-control-label">Program</label>
                                    <select class="custom-select form-control" id="program" name="program">
                                        <option value="">-- select --</option>
                                        <?php foreach ($programsCurr as $programCurr): ?>
                                            <option value="<?= $programCurr['prog_code'] ?>">
                                                <?= $programCurr['prog_code'] . ' - ' . $programCurr['prog_curriculum_desc'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="level" class="required-control-label">Year of study</label>
                                    <select class="custom-select form-control" id="level" name="level">
                                        <option value="">-- select --</option>
                                        <?php foreach ($academicLevels as $level): ?>
                                            <option value="<?= $level['academic_level'] ?>"><?= $level['academic_level_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="group-id" class="required-control-label">Group</label>
                                    <select class="custom-select form-control" id="group-id" name="group-id">
                                        <option value="">-- select --</option>
                                        <?php foreach ($groups as $group): ?>
                                            <option value="<?= $group['study_group_id'] ?>"><?= $group['study_group_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="semester-id" class="required-control-label">Semester</label>
                                    <select class="custom-select form-control" id="semester-id" name="semester-id">
                                        <option value="">-- select --</option>
                                        <?php foreach ($semesters as $semester): ?>
                                            <option value="<?= $semester['progCurrSemGroupId'] ?>"><?= 'Semester ' . $semester['code'] . ' - ' . $semester['description'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div>
                                <button type="submit" class="btn btn-success">Apply</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
$activeFiltersUrl = Url::to(['/courseRegistration/register-for-courses/active-filters', 'actionId' => $actionId]);

$courseFiltersJs = <<< JS
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
            $('#program').val(courseFilters.progCode);
            $('#level').val(courseFilters.level).change();
            $('#semester-id').val(courseFilters.semesterId).change();
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
    });
}
JS;
$this->registerJs($courseFiltersJs, yii\web\View::POS_READY);