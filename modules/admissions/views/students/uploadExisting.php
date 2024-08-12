<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 12/11/2023
 * @time: 11:09 AM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var array $duplicateStudents
 */

use app\helpers\SmisHelper;
use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<?php
echo SmisHelper::createBreadcrumb([
    'Admissions' => Url::to(['/admissions']),
    'Upload existing students' => null
]);
?>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Please read instructions below:
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body card-instructions">
                        <ul>
                            <li>
                                This interface is only used to upload old students' data. These students will join into
                                <span class="text-danger">Year 1, Semester 1</span>
                                of their respective programs.
                            </li>
                            <li>
                                New students will be admitted using the
                                <span class="text-danger">Students' Registration module</span>.
                                <a href="<?=Url::to(['/student-registration'])?>" target="_blank" class="btn-link">
                                    Click on this link
                                </a>
                            </li>
                            <li>
                                This interface <span class="text-danger">MUST NOT</span> be used to promote students. That will be done
                                using the progression interface.
                                <a href="<?=Url::to(['/exam-management/progression'])?>" target="_blank" class="btn-link">
                                    Click on this link
                                </a>
                            </li>
                            <li>
                                <a href="<?=Url::to(['students/template-download'])?>" target="_blank" class="btn-link reg-file-download-btn">
                                    <i class="fas fa-download" aria-hidden="true"></i>
                                    Click on this to download
                                </a>
                                the Excel file template and save it as an <span class="text-danger">Excel Workbook</span> before upload.
                            </li>
                            <li>You <span class="text-danger">MUST NOT</span> change the order of columns in the template.</li>
                            <li>
                                <a href="<?=Url::to(['students/upload-mapper'])?>" target="_blank" class="btn-link reg-file-download-btn">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                    Click on this to view
                                </a>
                                the data mapping to be used in populating additional data for the template.
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Upload existing students
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="upload-students-form" onsubmit="return false" method="post" action="#" enctype="multipart/form-data">
                            <div class="loader"></div>

                            <div class="error-display alert text-center" role="alert"></div>

                            <div class="duplicates"></div>

                            <div class="form-group row">
                                <label for="template" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                                    Excel Template
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="file" class="form-control" id="template" name="template" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                                    <small class="text-muted">File must be of extension .xlsx</small><br>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                                    <button id="btn-upload-students" class="btn btn-success">Submit</button>
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
$uploadUrl = Url::to('save-existing');

$uploadScript = <<< JS
const uploadUrl = '$uploadUrl';

const studentsUploadForm = $('#upload-students-form');
studentsUploadForm.validate();

const uploadLoader = $('#upload-students-form > .loader');
uploadLoader.html(loader);
uploadLoader.hide();

const uploadError =  $('#upload-students-form > .error-display');
uploadError.hide();

$('.duplicates > h6').remove();
$('.duplicates > p').remove();

$('#btn-upload-students').click(function (e){
    e.preventDefault();
    
    $('.duplicates > h6').remove();
    $('.duplicates > p').remove();

    if(studentsUploadForm.valid()){
        if(confirm('Upload students in template?')){
            uploadError.hide();
            uploadLoader.show();
            
            let formData = new FormData(studentsUploadForm[0]);
            
            $.ajax({
                url: uploadUrl,
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData
            }).done(function (data){
                uploadLoader.hide();
                if(data.success){
                    successToaster(data.message);
                    
                    let duplicateTitleEl = $("<h6/>")
                       .attr("class", "text-center")
                       .text("These students already exist and were therefore not saved");
                    
                    let duplicateListEl = $("<p/>")
                        .attr("class", "text-center")
                        .text(data.duplicateStudents.join());
              
                    $('.duplicates').append(duplicateTitleEl).append(duplicateListEl);
                } else {
                    uploadError.html(data.message);
                    uploadError.show(); 
                    errorToaster(data.message);
                }
            }).fail(function (data){
                uploadLoader.hide();
                uploadError.html(data.responseText)
                uploadError.show();
            });
        }
    }else{
        uploadLoader.hide();
        uploadError.html('There were errors below, correct them and try submitting again.')
        uploadError.show();
    }
});
JS;
$this->registerJs($uploadScript, yii\web\View::POS_READY);


