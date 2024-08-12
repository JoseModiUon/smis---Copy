<?php

use app\assets\JstreeAsset;
use yii\helpers\Html;

JstreeAsset::register($this);
$this->title = '';
?>
    <div class="container examManagement-default-index">

        <header class="pb-1 mb-4 border-bottom fs-4 d-flex flex-col text-primary">
            <?= Html::a(
                '<span><i class="bi bi-house fs-4"></i>  Home</span>',
                ['/'],
                ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
            )
            ?> >
            <?= Html::a(
                '<span><i class="bi bi-person-rolodex fs-4"></i>  Exam Management </span>',
                ['/exam-management'],
                ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
            )
            ?>
            <div class="row justify-content-center align-items-center fs-6 flex-fill pt-2">
                <div class="col d-flex flex-row ">
                    <i class="bi bi-search fs-6 pr-2"></i>
                    <label class="w-100">
                        <input id="menu-search-input" type="text" class="px-2 border-0 border-bottom w-100 f-5 bg-transparent" placeholder="Menu Search" style="outline: none;" />
                    </label>
                    <span id="menu-clear" class="fw-bold text-danger" style="cursor: pointer"><i class="bi bi-x-octagon-fill"></i></span>
                </div>
            </div>
        </header>
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-2 menu-container">
            <div class="col menu-tree-container0"></div>
            <div class="col menu-tree-container1"></div>
            <div class="col menu-tree-container2"></div>
            <div class="col menu-tree-container3"></div>
        </div>
    </div>

<?php
$jsonMenu = [
    "[
        {'text' : 'Marks','a_attr':{'href':'javascript:void()'},
        'children' : [
            { 'text' : 'Marks Entry','a_attr' : {'href':'exam-management/reports/marks-entry'},'type':'links' },
            { 'text' : 'Marks Publishing','a_attr' : {'href':'exam-management/publish-marks/programmes'},'type':'links' },
        ]}
    ]",
    "[
        {'text' : 'Progression','a_attr':{'href':'javascript:void()'},
        'children' : [
            { 'text' : 'Semester progression','a_attr' : {'href':'exam-management/progression/index'},'type':'links' },
        ]}
    ]",
    "[
         {'text' : 'Requirements','a_attr':{'href':'javascript:void()'},
        'children' : [
            { 'text' : 'Programme Curriculum Course Group','a_attr' : {'href':'exam-management/prog-curr-course-grp'},'type':'links' },
            { 'text' : 'Program Curriculum Requirements','a_attr' : {'href':'exam-management/programmes'},'type':'links' },
            { 'text' : 'Final Exam Processing','a_attr' : {'href':'exam-management/final-exam-processing/process'},'type':'links' },
        ]}
    ]",
    "[
        {'text' : 'Reports','a_attr':{'href':'javascript:void()'},
        'children' : [
            { 'text' : 'Blank Individual Marksheet','a_attr' : {'href':'exam-management/reports/blank-marksheet'},'type':'links' },
            { 'text' : 'Provisional  Transcript','a_attr' : {'href':'exam-management/reports/provisional-transcript'},'type':'links' },
            { 'text' : 'Provisional  Full Transcript','a_attr' : {'href':'exam-management/reports/full-provisional-transcript'},'type':'links' },
            { 'text' : 'Full Marks Entry List','a_attr' : {'href':'exam-management/marks-entry-list/index'},'type':'links' },
            { 'text' : 'Consolidated Marksheet','a_attr' : {'href':'exam-management/reports/view-consolidated-marksheet'},'type':'links' },
            { 'text' : 'Senate Reports','a_attr' : {'href':'exam-management/reports/senate-reports'},'type':'links' },
            { 'text' : 'Student Academic Progress (Summarized)','a_attr' : {'href':'/exam-management/student-academic-progress/summarized'},'type':'links' },
            { 'text' : 'Student Academic Progress (Detailed)','a_attr' : {'href':'/exam-management/student-academic-progress/detailed'},'type':'links' },
        ]}
    ]"
];

$this->registerJs(
    <<<JS
let clearBtn = $("#menu-clear");
clearBtn.hide();

$.jstree.defaults.plugins = ['html_data','types',"search"];
$.jstree.defaults.search = {case_insensitive: false,show_only_matches: true}
$.jstree.defaults.state.preserve_loaded = true;
$.jstree.defaults.types = {"default":{"icon":"bi bi-folder-fill text-success pr-3"},"links":{"icon":"bi bi-link text-primary"}};
    $("#menu-search-input").on('keyup change',function() {
        $.each($('div.menu-container > div'),function(){ $(this).jstree(true).show_all(); });
        $('[class*="menu-tree-container"]').jstree('search', $(this).val());
        // $('.menu-tree-container').jstree('search', $(this).val());
        $("#menu-search-input").val() ? clearBtn.show() : clearBtn.hide();
    });
    clearBtn.on('click',function () {
        $('[class*="menu-tree-container"]').jstree("clear_search");
        $("#menu-search-input").val('').trigger('change');
    });
JS
);

//$m =[$country,$cohort,$acad,$programme,$org];

menu($this, $jsonMenu);

function menu($t, $m): void
{
    $i = 0;
    foreach ($m as $jsonMu) {
        $t->registerJs(
            <<<JS
        $('div.menu-tree-container$i')
    .jstree({
        'core' : { 'data' : $jsonMu },
    })
    .on('ready.jstree', function () {  $(this).jstree('open_all') } )
    .on('search.jstree', function (nodes, str) {
        if (str.nodes.length===0) {
            $(this).jstree(true).hide_all();
        }
    })
    .on("select_node.jstree", function (e, data) {
        if((data.node.a_attr.href).indexOf('void(') === -1){
            if( typeof (data.node.a_attr.target) == "undefined" )
                document.location = data.node.a_attr.href;
            else
                window.open( data.node.a_attr.href, data.node.a_attr.target );
        }
    });
    
JS,
        );
        $i++;
    }
}
