<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var array $submittedDocuments
 * @var string $admRefNo
 * @var bool $canBeAdmitted
 */

use app\modules\studentRegistration\helpers\SmisHelper;
use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<?php
echo \app\helpers\SmisHelper::createBreadcrumb([
    'Student registration' => Url::to(['/student-registration']),
    'Student to verify' => Url::to(['/student-registration/documents']),
    'Verify uploaded Documents > Admission Ref #' . $admRefNo => null
]);
?>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Click on a document to verify
                        </h3>
                    </div>
                    <div class="card-body">
                    <?php if($canBeAdmitted):?>
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-12">
                                <div class="bg-success text-center" style="padding:20px 0; border-radius: .25rem">
                                    All required documents have been approved.
                                </div>
                            </div>
                        </div>
                    <?php else:?>
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-12">
                                <div class="bg-warning text-center" style="padding:20px 0; border-radius: .25rem">
                                    Only students with fully approved documents can be cleared.
                                </div>
                            </div>
                        </div>
                    <?php endif;?>

                    <div class="loader"></div>

                    <div class="error-display alert text-center" role="alert"></div>

                    <?php if($canBeAdmitted):?>
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-12">
                            <button id="btn-clear-student" class="btn btn-success float-right">
                                Clear student
                            </button>
                        </div>
                    </div>
                    <?php else:?>
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-12">
                            <button disabled class="btn btn-default float-right">
                                Clear student
                            </button>
                        </div>
                    </div>
                    <?php endif;?>

                    <div class="row">
                        <div class="d-flex align-items-start">
                            <!-- Uploaded files -->
                            <?php
                                if(!empty($submittedDocuments)):
                            ?>
                            <div class="col-6">
                                <div class="nav flex-column nav-tabs me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <?php
                                    $index = 0;
                                    foreach ($submittedDocuments as $submittedDocument):
                                        $docId = $submittedDocument['requiredDocument']['document']['document_id'];
                                        $docName = $submittedDocument['requiredDocument']['document']['document_name'];
                                        $docStatus = $submittedDocument['verify_status'];

                                        $docIcon = '';
                                        if($docStatus === 'PENDING'){
                                            $docIcon = '<i class="fa fa-clock-o text-info" aria-hidden="true"></i>';
                                        }elseif ($docStatus === 'APPROVED'){
                                            $docIcon = '<i class="fas fa-check text-success" aria-hidden="true"></i>';
                                        }elseif ($docStatus === 'NOT_APPROVED'){
                                            $docIcon = '<i class="fas fa-times text-danger" aria-hidden="true"></i>';
                                        }

                                        $activeClass = 'active';
                                        if($index > 0){
                                            $activeClass = '';
                                        }
                                    ?>
                                    <button class="nav-link btn-link text-start <?=$activeClass?> verify-docs-btn-link" id="v-pills-doc-<?=$docId?>-tab" data-doc-id="<?=$docId?>" data-bs-toggle="pill" data-bs-target="#v-pills-doc-<?=$docId?>" type="button" role="tab" aria-controls="v-pills-doc-<?=$docId?>" aria-selected="true">
                                        <i class="fas fa-file" aria-hidden="true"></i>
                                        <?=$docName?>
                                        &nbsp;
                                        <?=$docIcon?>
                                    </button>
                                    <?php $index++;
                                    endforeach;?>
                                </div>
                            </div>

                            <!-- Uploaded files action forms -->
                            <div class="col-6">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <?php
                                    $index = 0;
                                    foreach ($submittedDocuments as $submittedDocument):
                                        $submittedDocId = $submittedDocument['student_document_id'];
                                        $docId = $submittedDocument['requiredDocument']['document']['document_id'];
                                        $docName = $submittedDocument['requiredDocument']['document']['document_name'];
                                        $docStatus = $submittedDocument['verify_status'];
                                        $docComments = $submittedDocument['doc_comments'];

                                        $activeClasses = '';
                                        if($index === 0){
                                            $activeClasses = 'show active';
                                        }

                                        $docDownloadUrl = SmisHelper::regDocDownloadUrl($submittedDocId, $admRefNo);
                                    ?>
                                    <div class="tab-pane fade <?=$activeClasses?>" id="v-pills-doc-<?=$docId?>" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                                        <div class="loader"></div>
                                        <div class="error-display alert text-center" role="alert"></div>
                                        <div class="row">
                                            <div class="text-start" style="margin-bottom: 20px;">
                                                <?=$docName?>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <dl class="row">
                                                        <dt class="col-sm-4">Status </dt>
                                                        <dd class="col-sm-8"><?=$docStatus?></dd>
                                                        <dt class="col-sm-4">Comments</dt>
                                                        <dd class="col-sm-8"><?=$docComments?></dd>
                                                    </dl>
                                                </div>
                                            </div>
                                            <form id="doc-<?=$docId?>-form" class="verify-docs-form" method="post" action="#" onsubmit="return false;">
                                                <?php // We want to know the first document so that we can initialize its validations with jquery
                                                if($index === 0):?>
                                                <input type="hidden" id="top-document" name="topDocument" value="<?=$docId?>">
                                                <?php endif;?>

                                                <input type="hidden" id="doc-<?=$docId?>-id" name="doc<?=$docId?>Id" value="">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <a href="<?=$docDownloadUrl?>" target="_blank" class="btn reg-file-download-btn">
                                                                <i class="fas fa-download" aria-hidden="true"></i>
                                                                Click to download this file
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="doc-<?=$docId?>-status" class="required-control-label">Status</label>
                                                            <select type="text" class="form-control verify-docs-status" id="doc-<?=$docId?>-status" data-doc-id="<?=$docId?>" name="doc<?=$docId?>Status" required>
                                                                <option value=""> -Select status- </option>
                                                                <option value="APPROVED"> APPROVED</option>
                                                                <option value="NOT_APPROVED"> NOT APPROVED</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="doc-<?=$docId?>-comments">Comments</label>
                                                            <textarea class="form-control" rows="3" id="doc-<?=$docId?>-comments" name="doc<?=$docId?>Comments"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="submit" data-doc-id="<?=$docId?>" data-submitted-doc-id="<?=$submittedDocId?>" class="btn btn-success submit-document">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php $index++;
                                    endforeach;?>
                                </div>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$updateDocsUrl = Url::to(['/student-registration/documents/update']);
$clearStudentUrl = Url::to(['/student-registration/documents/clear-student']);

$verifyDocsJs = <<< JS
const admRefNo = '$admRefNo';
const updateDocsUrl = '$updateDocsUrl';
const clearStudentUrl = '$clearStudentUrl';

let cardLoader = $('.card-body > .loader');
cardLoader.html(loader);
cardLoader.hide();

let cardErrorDisplay =  $('.card-body > .error-display');
cardErrorDisplay.hide();

let docId = $('#top-document').val();
var docStatus;
addFormValidation(docId);

let docsLoader;
let docsErrorDisplay;
initializeStatusLoaders(docId);

$('.verify-docs-btn-link').click(function (e){
    docId = $(this).attr('data-doc-id');
    addFormValidation(docId);
    initializeStatusLoaders(docId);
    
});

function addFormValidation(docId){
    docStatus = 'doc' + docId + 'Status'
    $('#doc-' + docId + '-form').validate({
        rules: {
            docStatus: {
                required: true
            }
        }
    });
}

function initializeStatusLoaders(docId){
    docsLoader = $('#v-pills-doc-' + docId + ' > .loader');
    docsLoader.html(loader);
    docsLoader.hide();

    docsErrorDisplay =  $('#v-pills-doc-' + docId + ' > .error-display');
    docsErrorDisplay.hide();
}

$('.verify-docs-status').on('change', function (e){
    let status = $(this).val();
    let docId = $(this).attr('data-doc-id');
    let commentsId = 'doc-' + docId + '-comments';
    
    $('#doc-' + docId + '-form').validate();
   
    if(status === 'NOT_APPROVED'){
        $("label[for='" + commentsId + "']").addClass('required-control-label');
        $("#" + commentsId).attr('required', true);
        $("#" + commentsId).rules('add', {required: true});
    }else{
        $("label[for='" + commentsId + "']").removeClass('required-control-label');
        $("#" + commentsId).attr('required', false);
        $("#" + commentsId).rules('remove');
        $("#" + commentsId + "-error").remove();
    }
});

$('.submit-document').click(function (e){
    let docId = $(this).attr('data-doc-id');
    let docsForm = $('#doc-' + docId + '-form');
    let submittedDocId = $(this).attr('data-submitted-doc-id');
    let statusId = 'doc-' + docId + '-status';
    let status = $('#' + statusId).val();
    let commentsId = 'doc-' + docId + '-comments';
    let comments = $('#' + commentsId).val();
    
    if(docsForm.valid()){
        if(confirm('Save changes?')){
            docsErrorDisplay.hide();
            docsLoader.show();
            let postData = {
                'docId': docId,
                'submittedDocId': submittedDocId,
                'status': status,
                'comments': comments
            };
            $.ajax({
                url: updateDocsUrl,
                type: 'POST',
                data: postData,
                dataType: 'json',
                encode: true            
            }).done(function (data){
                docsLoader.hide();
                if(!data.success){
                    errorToaster(data.message);
                    docsErrorDisplay.html(data.message);
                    docsErrorDisplay.show();
                }
            }).fail(function (data){
                docsLoader.hide();
                docsErrorDisplay.html(data.responseText) 
                docsErrorDisplay.show(); 
            });
        }
    }else{
        docsLoader.hide();
        docsErrorDisplay.html('There were errors below, correct them and try submitting again.');   
        docsErrorDisplay.show();
    }
});

$('#btn-clear-student').click(function (e){
    if(confirm('Clear this student?')){
        cardErrorDisplay.hide();
        cardLoader.show();
        $.ajax({
            url: clearStudentUrl,
            type: 'POST',
            data: {'admRefNo': admRefNo},
            dataType: 'json',
            encode: true            
        }).done(function (data){
            cardLoader.hide();
            if(!data.success){
                errorToaster(data.message);
                cardErrorDisplay.html(data.message);
                cardErrorDisplay.show();
            }
        }).fail(function (data){
            cardLoader.hide();
            cardErrorDisplay.html(data.responseText) 
            cardErrorDisplay.show();
        });
    }
});
JS;
$this->registerJs($verifyDocsJs, yii\web\View::POS_READY);

