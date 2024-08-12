<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/12/2024
 * @time: 2:45 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var array $charge
 *  * @var array $frequencies
 */

use app\helpers\SmisHelper;
use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<?php
$breadcrumbUrls = [
    'Fees management' => Url::to(['/fees-management']),
    'Programs' => Url::to(['/fees-management/program-charges/programs']),
    'Program charges' => Url::to(['/fees-management/program-charges/list-charges', 'progCurrId' => $charge['prog_curr_id']]),
    'Edit charge to program' => null
];
echo SmisHelper::createBreadcrumb($breadcrumbUrls);
?>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?=$charge['prog_code'] . ' ' . $charge['prog_short_name']?> | <?=$charge['fee_description']?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="prog-charges-form" onsubmit="return false" method="post" action="#">

                            <div class="prog-charges">
                                <div class="loader"></div>
                                <div class="error-display alert text-center" role="alert"></div>
                            </div>

                            <input type="hidden" class="form-control" name="chargeTypeId" value="<?=$charge['charge_type_id']?>">

                            <div class="form-group row">
                                <label for="year" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                                    Academic Year
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="text" disabled class="form-control" id="year" name="year" value="<?=$charge['acad_session_name']?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="currency" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                                    Currency
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="text" disabled class="form-control" id="currency" name="currency" value="KES">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="start-date" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                                    Start Date
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="date" class="form-control" id="start-date" name="start-date" value="<?=$charge['start_date']?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="end-date" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                                    End Date
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="date" class="form-control" id="end-date" name="end-date" value="<?=$charge['end_date']?>">
                                    <small class="text-muted"> End date must not be less than start date</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="amount" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                                    Amount
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="amount" name="amount" value="<?=$charge['amount_charged']?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="freq" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                                    Frequency
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <select class="custom-select form-control" id="freq" name="freq">
                                        <option value="">-- select --</option>
                                        <?php foreach ($frequencies as $frequency): ?>
                                            <option value="<?= $frequency['billing_frequency_id'] ?>">
                                                <?= $frequency['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                                    <button type="submit" id="btn-update" class="btn btn-success">Update</button>
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
$updateProgramChargesUrl = Url::to(['/fees-management/program-charges/update']);
$billFreqId = $charge['billing_frequency_id'];

$chargesJs = <<< JS
$('#freq').val('$billFreqId');

const updateProgramChargesUrl = '$updateProgramChargesUrl';
const chargesLoader = $('.prog-charges > .loader');
chargesLoader.html(loader);
chargesLoader.hide();
const chargesErrorDisplay =  $('.prog-charges > .error-display');
chargesErrorDisplay.hide();

const progChargesForm = $('#prog-charges-form');
progChargesForm.validate({
    rules: {
        'start-date': {
            required: true
        },
        'end-date': {
            required: true
        },
        'amount': {
            required: true
        },
        'freq': {
            required: true
        }
    }
});

$('#btn-update').click(function (e){
    e.preventDefault();
    if(progChargesForm.valid() && confirm('Update program charge?')){
        if(new Date($('#end-date').val()) < new Date($('#start-date').val())) {
            alert('END DATE not be less than START DATE')
        } else {
            chargesErrorDisplay.hide();
            chargesLoader.show();
            $.ajax({
                url: updateProgramChargesUrl,
                type: 'POST',
                data: progChargesForm.serialize()
            }).done(function (data){
                chargesLoader.hide();
                 if(!data.success){
                    chargesErrorDisplay.html(data.message) 
                    chargesErrorDisplay.show();
                 }
            }).fail(function (data){
                 chargesLoader.hide();
                 chargesErrorDisplay.html(data.responseText) 
                 chargesErrorDisplay.show();
            });
        }
    }else {
        chargesLoader.hide();
        chargesErrorDisplay.html('There were errors below, correct them and try submitting again.');   
        chargesErrorDisplay.show();
    }
});
JS;
$this->registerJs($chargesJs, yii\web\View::POS_READY);
