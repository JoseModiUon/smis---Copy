<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 9/6/2023
 * @time: 10:24 AM
 */

use yii\helpers\Url;
?>

<div id="new-doc-modal" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="user-roles-modal-title" class="modal-title">
                    New document
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="new-doc-form" method="post" action="#" onsubmit="return false;">
                    <div class="loader"></div>
                    <div class="error-display alert text-center" role="alert"></div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="required-control-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="required-status" class="required-control-label">Required status</label>
                                <select id="required-status" class="form-control" name="required-status">
                                    <option value="">--select--</option>
                                    <option value="required">Required</option>
                                    <option value="Not required">Not required</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" class="form-control" name="description"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" id="btn-submit-new-doc" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$saveRegDocUrl = Url::to(['/student-registration/registration-documents/save']);

$docsJs = <<< JS
const saveRegDocUrl = '$saveRegDocUrl';
const regDocsForm = $('#new-doc-form');
const regDocsLoader = $('#new-doc-form > .loader');
regDocsLoader.html(loader);
regDocsLoader.hide();
const regDocsErrorDisplay =  $('#new-doc-form > .error-display');
regDocsErrorDisplay.hide();
regDocsForm.validate({
    rules: {
        'name': {
            required: true
        },
        'required-status': {
            required: true
        }
    }
});

$('#documents-grid-pjax').on('click', '#btn-new-doc', function (e){
    e.preventDefault();
    regDocsLoader.hide();
    regDocsErrorDisplay.hide();
    regDocsForm[0].reset();
    $('#new-doc-modal').modal('show');
});

$('#btn-submit-new-doc').click(function (e){
    e.preventDefault();
    if(regDocsForm.valid()){
        if(confirm('Submit new document?')){
            regDocsErrorDisplay.hide();
            regDocsLoader.show();
            $.ajax({
                url: saveRegDocUrl,
                type: 'POST',
                data: regDocsForm.serialize()    
            }).done(function (data){
                regDocsLoader.hide();
                if(!data.success){
                    regDocsErrorDisplay.html(data.message) 
                    regDocsErrorDisplay.show();
                }
            }).fail(function (data){
                regDocsLoader.hide();
                regDocsErrorDisplay.html(data.responseText) 
                regDocsErrorDisplay.show();
            });
        }
    }else{
        regDocsLoader.hide();
        regDocsErrorDisplay.html('There were errors below, correct them and try submitting again.');   
        regDocsErrorDisplay.show();
    }
});
JS;
$this->registerJs($docsJs, yii\web\View::POS_READY);
