<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/29/2023
 * @time: 11:41 AM
 */

/**
 * @var string $filterLevel
 */
use yii\helpers\Url;

$publishUrl = Url::to(['/exam-management/publish-marks/publish']);

if($filterLevel === 'courses'){
    $grid = '#courses-grid';
}else{
    $grid = '#marks-grid';
}

$coursesJs = <<< JS
const grid = '$grid';
const filterLevel = '$filterLevel';
const publishUrl  = '$publishUrl';
const coursesLoader = $('.courses > .loader');
coursesLoader.html(loader);
coursesLoader.hide(); 
const coursesErrorDisplay =  $('.courses > .error-display');
coursesErrorDisplay.hide

$(grid + '-pjax').on('click', '#publish-marks-btn', function (e){
    e.preventDefault();
    let ids = getSelectedIds(grid);
    if(ids.length === 0){
        console.log('No records have been selected for publishing');
        coursesErrorDisplay.html('No records have been selected for publishing');
        coursesErrorDisplay.show();
    }else{
        if(confirm('Publish marks?')){
            coursesErrorDisplay.hide();
            coursesLoader.show();
            
            let postData;
            if(filterLevel == 'courses'){
                postData = {
                    'marksheetIds' : ids
                }
            }else{
                 postData = {
                    'studentCoursesIds' : ids
                }
            }
         
            $.ajax({
                url: publishUrl,
                type: 'POST',
                data: postData
            }).done(function (data){
                coursesLoader.hide();
                if(!data.success){
                    coursesErrorDisplay.html(data.message);
                    coursesErrorDisplay.show();
                }
            }).fail(function (data){
                coursesLoader.hide();
                coursesErrorDisplay.html(data.responseText);
                coursesErrorDisplay.show();
            });
        }
    }
});

// Get selected rows
function getSelectedIds(gridSelector) {
    let keys = $(gridSelector).yiiGridView('getSelectedRows');
    let ids = [];
    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');

        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    return [...new Set(ids)]
}
JS;
$this->registerJs($coursesJs, yii\web\View::POS_READY);
