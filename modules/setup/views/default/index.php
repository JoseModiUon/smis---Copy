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
            '<span><i class="bi bi-person-rolodex fs-4"></i>  Setup </span>',
            ['/setup'],
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
        <div class="col menu-tree-container4"></div>
        <!-- <div class="col menu-tree-container5"></div> -->
        <!--            <div class="col menu-tree-container"></div>-->
    </div>
</div>
<?php

$extras = [
    "
{ 'text' : 'Organisation Unit Heads','a_attr' : {'href':'setup/org-unit-head'},'type':'links' },
{ 'text' : 'Organisation Unit Types','a_attr' : {'href':'setup/org-unit-types'},'type':'links' }, 
{ 'text' : 'Organisation Unit History','a_attr' : {'href':'setup/org-unit-history'},'type':'links' },				
{ 'text' : 'Study Center','a_attr' : {'href':'setup/org-study-centre'},'type':'links' },
{ 'text' : 'Study Group','a_attr' : {'href':'setup/org-study-group'},'type':'links' },
{ 'text' : 'Study Center Group','a_attr' : {'href':'setup/org-study-centre-group'},'type':'links' },
{ 'text' : 'Withdrawal Type','a_attr' : {'href':'setup/sm-withdrawal-type'},'type':'links' },
{ 'text' : 'Programme Level','a_attr' : {'href':'setup/org-prog-level'},'type':'links' },
{ 'text' : 'Unit Course','a_attr' : {'href':'setup/org-unit-course'},'type':'links' },
{ 'text' : 'Programme Category','a_attr' : {'href':'setup/org-prog-category'},'type':'links' },
{ 'text' : 'Programme Type','a_attr' : {'href':'setup/org-prog-type'},'type':'links' },
"
];
$jsonMenu = [
    "
        [
            {'text' : 'System Administration','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Country','a_attr' : {'href':'setup/org-country'},'type':'links' },
                { 'text' : 'Building','a_attr' : {'href':'setup/org-building'},'type':'links' },
                { 'text' : 'Rooms','a_attr' : {'href':'setup/org-rooms'},'type':'links' },
                { 'text' : 'Room Type','a_attr' : {'href':'setup/org-room-type'},'type':'links' },
                { 'text' : 'Room Floors','a_attr' : {'href':'setup/org-room-floors'},'type':'links' },
                { 'text' : 'Room Status','a_attr' : {'href':'setup/org-room-status'},'type':'links' },
                { 'text' : 'Semester Code','a_attr' : {'href':'setup/org-semester-code'},'type':'links' },
                { 'text' : 'Academic Levels','a_attr' : {'href':'setup/org-academic-levels'},'type':'links' },                
                { 'text' : 'Academic Session Status','a_attr' : {'href':'setup/org-acad-session-status'},'type':'links' },
                { 'text' : 'Approvers','a_attr' : {'href':'setup/sm-approver'},'type':'links' },
                { 'text' : 'Programme Levels','a_attr' : {'href':'setup/org-prog-level'},'type':'links' },
            ]}
        ]
    ",
    "
        [	
            {'text' : 'Student Setup','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Cohort','a_attr' : {'href':'setup/org-cohort'},'type':'links' },
                { 'text' : 'Cohort Session','a_attr' : {'href':'setup/org-cohort-session'},'type':'links' },
                
				{ 'text' : 'Academic Session','a_attr' : {'href':'setup/org-academic-session'},'type':'links' },
                
                { 'text' : 'Academic Session Semester','a_attr' : {'href':'setup/org-academic-session-semester'},'type':'links' },
                
            ]}
        ],
    ",

    "
        [	
            {'text' : 'Examination Management','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Exam Mode','a_attr' : {'href':'setup/ex-mode'},'type':'links' }, 
                { 'text' : 'Curriculum Option','a_attr' : {'href':'setup/org-prog-curr-option'},'type':'links' },
                { 'text' : 'Curriculum Option Courses','a_attr' : {'href':'setup/org-prog-curr-option-courses'},'type':'links' },
            ]}
        ],
    ",
    "
        [    
            {'text' : 'Course Registration','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Courses','a_attr' : {'href':'setup/org-courses'},'type':'links' },
                { 'text' : 'Programme Curriculum','a_attr' : {'href':'courseRegistration/org-programme-curriculum'},'type':'links' },
                { 'text' : 'Programme Curriculum Unit','a_attr' : {'href':'courseRegistration/org-prog-curr-unit'},'type':'links' },
                { 'text' : 'Programme Curriculum Semester','a_attr' : {'href':'courseRegistration/org-prog-curr-semester'},'type':'links' },
                { 'text' : 'Programme Curriculum Course','a_attr' : {'href':'courseRegistration/org-prog-curr-course'},'type':'links' },
                { 'text' : 'Course Prerequisite','a_attr' : {'href':'courseRegistration/org-course-prerequisite'},'type':'links' },
                { 'text' : 'Programme Curriculum Semester Group','a_attr' : {'href':'setup/org-prog-curr-semester-group'},'type':'links' },
                { 'text' : 'Billing Type','a_attr' : {'href':'/fees-management/bill-type'},'type':'links' },
               
            ]}
        ],
    ",
    "
        [    
            {'text' : 'Organisational Structure','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Organisation Unit','a_attr' : {'href':'setup/org-unit'},'type':'links' },
                { 'text' : 'Programmes','a_attr' : {'href':'setup/org-programmes'},'type':'links' },
                ]
            }
        ],
    ",




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
