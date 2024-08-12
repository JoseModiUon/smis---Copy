<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 9/8/2023
 * @time: 12:03 PM
 */

declare(strict_types=1);

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string[] $docs
 * @var string[] $categories
 * @var string $scope
 */

use yii\helpers\Url;

$this->title = $title;
?>

    <!-- Content Header (Page header) -->
<?php
echo \app\helpers\SmisHelper::createBreadcrumb([
    'Student registration' => Url::to(['/student-registration']),
    'Registration documents setup' => Url::to(['/student-registration/registration-documents']),
    'Associate documents with categories' => null
]);
?>
    <!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form id="associate-docs-with-cats-form" onsubmit="return false" method="post" action="#">
                            <div class="loader"></div>
                            <div class="error-display alert text-center" role="alert"></div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            Student categories
                                        </h3>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="check-box-all-cats" class="custom-control-input">
                                            <label for="check-box-all-cats" class="custom-control-label">Select All Categories</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php foreach ($categories as $category):?>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="<?='cat-' . $category['std_category_id']?>" class="custom-control-input check-box-cat categories" name="<?='categories[' . $category['std_category_id'] . ']';?>">
                                            <label for="<?='cat-' . $category['std_category_id']?>" class="custom-control-label"><?=$category['std_category_name']?></label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Registration documents
                                            </h3>
                                        </div>
                                        <div class="col-12 form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="check-box-all-docs" class="custom-control-input">
                                                <label for="check-box-all-docs" class="custom-control-label">Select All Documents</label>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php foreach ($docs as $doc): ?>
                                        <div class="col-6 form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="<?='doc-' . $doc['document_id']?>" class="custom-control-input check-box-doc documents" name="<?='documents[' . $doc['document_name'] . ']';?>">
                                                <label for="<?='doc-' . $doc['document_id']?>" class="custom-control-label">
                                                <?php
                                                if($doc['required']){
                                                    echo $doc['document_name'] . ' <small class="text-success">required</small>';
                                                }
                                                else {
                                                    echo $doc['document_name'] . ' <small class="text-danger">Not required</small>';
                                                }
                                                ?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                                    <button type="submit" id="btn-submit-associate-docs" class="btn btn-success">Submit</button>
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
$submitUrl = '';
if($scope === 'add-docs'){
    $submitUrl =  Url::to(['/student-registration/registration-documents/add-doc-to-category']);
}
elseif ($scope === 'remove-docs') {
    $submitUrl =  Url::to(['/student-registration/registration-documents/remove-doc-from-category']);
}

$assocDocsWithCatsJs = <<< JS
const submitUrl = '$submitUrl';
const docsLoader = $('#associate-docs-with-cats-form > .loader');
docsLoader.html(loader);
docsLoader.hide();
const docsErrorDisplay =  $('#associate-docs-with-cats-form > .error-display');
docsErrorDisplay.hide();

$("#check-box-all-cats").click(function () {
    $(".check-box-cat").prop('checked', $(this).prop('checked'));
});
    
$(".check-box-cat").change(function(){
    if (!$(this).prop("checked")){
        $("#check-box-all-cats").prop("checked",false);
    }
});

$("#check-box-all-docs").click(function () {
    $(".check-box-doc").prop('checked', $(this).prop('checked'));
});
    
$(".check-box-doc").change(function(){
    if (!$(this).prop("checked")){
        $("#check-box-all-docs").prop("checked",false);
    }
});
    
$('#btn-submit-associate-docs').click(function (e){
    e.preventDefault();
    let documentIds = [];
    let categoryIds = [];
    
    $('input.categories:checkbox:checked').each(function (e){
        categoryIds.push($(this).attr('id').split('-')[1]);
    });
    
    $('input.documents:checkbox:checked').each(function (e){
        documentIds.push($(this).attr('id').split('-')[1]);
    });
    
    if(categoryIds.length === 0 || documentIds.length === 0){
        docsLoader.hide();
        docsErrorDisplay.html('You must select a category and document to add or remove!');   
        docsErrorDisplay.show();
    }else{
        if(confirm('Submit documents?')){
            docsErrorDisplay.hide();
            docsLoader.show();
            $.ajax({
                url: submitUrl,
                type: 'POST',
                data: {
                    'documentIds' : documentIds,
                    'categoryIds' : categoryIds
                }    
            }).done(function (data){
                docsLoader.hide();
                if(!data.success){
                    docsErrorDisplay.html(data.message) 
                    docsErrorDisplay.show();
                }
            }).fail(function (data){
                docsLoader.hide();
                docsErrorDisplay.html(data.responseText) 
                docsErrorDisplay.show();
            });
        }
    }
});
JS;
$this->registerJs($assocDocsWithCatsJs, yii\web\View::POS_READY);
