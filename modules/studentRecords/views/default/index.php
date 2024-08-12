<?php

use app\assets\JstreeAsset;
use yii\helpers\Html;

JstreeAsset::register($this);
$this->title = '';
?>
<div class="container studentRecords-default-index">

    <header class="pb-1 mb-4 border-bottom fs-4 d-flex flex-col text-primary">
        <?= Html::a(
            '<span><i class="bi bi-house fs-4"></i>  Home</span>',
            ['/'],
            ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
        )
        ?> >
        <?= Html::a(
            '<span><i class="bi bi-person-rolodex fs-4"></i>  Student Records </span>',
            ['/student-records'],
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
    </div>
</div>
<?php
$jsonMenu = "
[
    {'text' : 'Student  Record Setup','a_attr':{'href':'javascript:void()'},
    'children' : [
        { 'text' : 'Country','a_attr' : {'href':'setup/org-country'},'type':'links' },
        { 'text' : 'Student Sponsor','a_attr' : {'href':'student-records/sm-student-sponsor'},'type':'links' },
        { 'text' : 'Degree Types','a_attr' : {'href':'setup/org-prog-type'},'type':'links' },
        { 'text' : 'Degree Programmes','a_attr' : {'href':'setup/org-programmes'},'type':'links' },
        { 'text' : 'Grading System','a_attr' : {'href':'student-records/ex-grading-system'},'type':'links' },
        { 'text' : 'Programme Curriculum','a_attr' : {'href':'courseRegistration/org-programme-curriculum'},'type':'links' },
        { 'text' : 'Student Categories','a_attr' : {'href':'student-records/student-category'},'type':'links' },
        { 'text' : 'Student Status','a_attr' : {'href':'student-records/student-status'},'type':'links' },
        { 'text' : 'Intakes','a_attr' : {'href':'student-records/sm-intakes'},'type':'links' },
        { 'text' : 'Intake Source','a_attr' : {'href':'student-records/sm-intake-source'},'type':'links' },
        { 'text' : 'Withdrawal Types','a_attr' : {'href':'student-records/sm-withdrawal-type'},'type':'links' },

    ]}
]
";
$namechange = "
[
    {'text' : 'Student Requests',
    'children' : [
        { 'text' : 'Name Change Request Approval','a_attr' : {'href':'/student-records/sm-name-change'},'type':'links' },
        { 'text' : 'Withdrawal/ Deferment Request Approval','a_attr' : {'href':'/student-records/sm-withdrawal-request'},'type':'links' },
       
    ]}
]
";
$reports = "
[
    {'text' : 'Reports',
    'children' : [
        { 'text' : 'Students per Sponsor','a_attr' : {'href':'/student-records/reports/students-per-sponsor'},'type':'links' },
        { 'text' : 'Students Nationality Stats','a_attr' : {'href':'/student-records/reports/student-nationality-stats'},'type':'links' },
        { 'text' : 'List of Foreign Students','a_attr' : {'href':'/student-records/reports/foreign-students-list'},'type':'links' },      
        { 'text' : 'Overall Student Statistics','a_attr' : {'href':'/student-records/reports/student-statistics'},'type':'links' },      
        { 'text' : 'Nominal Roll','a_attr' : {'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Admissions Analysis','a_attr' : {'href':'/student-records/reports/nominal-admissions-analysis'},'type':'links' },
                { 'text' : 'List of Students Per Session','a_attr' : {'href':'/student-records/reports/student-list-per-session'},'type':'links' },
                  { 'text' : 'Students in Session','a_attr' : {'href':'/student-records/reports/students-in-session'},'type':'links' },
				{ 'text' : 'Student Statistics Per Programme','a_attr' : {'href':'/student-records/reports/student-stats-per-session'},'type':'links' },
                { 'text' : 'Nominal Roll Per Class','a_attr' : {'href':'/student-records/reports/nominal-roll-per-class'},'type':'links' },
                { 'text' : 'Nominal Roll Per Unit','a_attr' : {'href':'/student-records/reports/nominal-roll-per-unit'},'type':'links' },
            ]},
		{ 'text' : 'Student Requests','a_attr' : {'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Name Change Request Report','a_attr' : {'href':'/student-records/sm-name-change/reports'},'type':'links' },
                { 'text' : 'Withdrawal Request Report','a_attr' : {'href':'/student-records/sm-withdrawal-request/reports'},'type':'links' },
            ]},
    
        
        
    ]}
]
";

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

$m = [$jsonMenu, $namechange, $reports];

menu($this, $m);

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
