<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 9/11/2023
 * @time: 9:25 AM
 */

use yii\helpers\Url;
?>

<div id="edit-doc-modal" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="user-roles-modal-title" class="modal-title">
                    Edit document
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-doc-form" method="post" action="#" onsubmit="return false;">
                    <div class="loader"></div>
                    <div class="error-display alert text-center" role="alert"></div>
                    <div class="row">
                        <input type="hidden" class="form-control" id="edit-id" name="id">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit-name" class="required-control-label">Name</label>
                                <input type="text" class="form-control" id="edit-name" name="name">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit-required-status" class="required-control-label">Required status</label>
                                <select id="edit-required-status" class="form-control" name="required-status">
                                    <option value="">--select--</option>
                                    <option value="required">Required</option>
                                    <option value="Not required">Not required</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit-description">Description</label>
                                <textarea id="edit-description" class="form-control" name="description"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" id="btn-update-doc" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$updateRegDocUrl = Url::to(['/student-registration/registration-documents/update']);
$fetchDocumentUrl = Url::to(['/student-registration/registration-documents/edit']);
$editDocsJs = <<< JS
const updateRegDocUrl = '$updateRegDocUrl';
const fetchDocumentUrl = '$fetchDocumentUrl';
const updateDocsForm = $('#edit-doc-form');
const updateDocsLoader = $('#edit-doc-form > .loader');
updateDocsLoader.html(loader);
updateDocsLoader.hide();
const updateDocsErrorDisplay =  $('#edit-doc-form > .error-display');
updateDocsErrorDisplay.hide();
updateDocsForm.validate({
    rules: {
        'name': {
            required: true
        },
        'required-status': {
            required: true
        }
    }
});

$('#documents-grid-pjax').on('click', '.edit-document', function (e){
    e.preventDefault();
    updateDocsLoader.hide();
    updateDocsErrorDisplay.hide();
    updateDocsForm[0].reset();
    $('#edit-doc-modal').modal('show');
    fetchDocument($(this).attr('documentId'));
});

function fetchDocument(documentId){
    updateDocsErrorDisplay.hide();
    updateDocsLoader.show();
    axios.get(fetchDocumentUrl, {
        params: {
		    docId: documentId
	    }
    })
    .then(response => {
        if(response.data.success){
            updateDocsLoader.hide();
            const document = response.data.document;
            $('#edit-id').val(document.document_id);
            $('#edit-name').val(document.document_name);
            $('#edit-description').val(document.document_desc);
            if(document.required){
                $('#edit-required-status').val('required').change();
            }else{
                $('#edit-required-status').val('Not required').change();
            }
        }else{
            updateDocsLoader.hide();
            updateDocsErrorDisplay.html('Fetching document failed: ' + response.data.message) 
            updateDocsErrorDisplay.show();
        }
    })
    .catch(error => {
        updateDocsLoader.hide();
        updateDocsErrorDisplay.html('Fetching document failed: ' + error.message) 
        updateDocsErrorDisplay.show();
    });
}

$('#btn-update-doc').click(function (e){
    e.preventDefault();
    if(updateDocsForm.valid()){
        if(confirm('Update this document?')){
            updateDocsErrorDisplay.hide();
            updateDocsLoader.show();
            $.ajax({
                url: updateRegDocUrl,
                type: 'POST',
                data: updateDocsForm.serialize()    
            }).done(function (data){
                updateDocsLoader.hide();
                if(!data.success){
                    updateDocsErrorDisplay.html(data.message) 
                    updateDocsErrorDisplay.show();
                }
            }).fail(function (data){
                updateDocsLoader.hide();
                updateDocsErrorDisplay.html(data.responseText) 
                updateDocsErrorDisplay.show();
            });
        }
    }else{
        updateDocsLoader.hide();
        updateDocsErrorDisplay.html('There were errors below, correct them and try submitting again.');   
        updateDocsErrorDisplay.show();
    }
});
JS;
$this->registerJs($editDocsJs, yii\web\View::POS_READY);
