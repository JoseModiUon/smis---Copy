<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @updatedAt 16/10/2023 11 am
 */

use app\assets\JstreeAsset;
use yii\helpers\Html;

JstreeAsset::register($this);
$this->title = 'smis - course registration';
?>
<div class="container courseRegistration-default-index">

    <header class="pb-1 mb-4 border-bottom fs-4 d-flex flex-col text-primary">
        <?= Html::a(
            '<span><i class="bi bi-house fs-4"></i>  Home</span>',
            ['/'],
            ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
        )
        ?> >
        <?= Html::a(
            '<span><i class="bi bi-card-heading fs-4"></i>  Course Registration </span>',
            ['/courseRegistration'],
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
$jsonMenu = [
    "[
        {
            'text' : 'Time Table','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Course Timetable','a_attr' : {'href':'courseRegistration/timetables'},'type':'links' },
            ]
		}
    ]",
    "[
        {
            'text' : 'Register for courses','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Teaching timetable','a_attr' : {'href':'courseRegistration/register-for-courses/timetables?actionId=teaching'},'type':'links' },
                { 'text' : 'Supplementary timetable','a_attr' : {'href':'courseRegistration/register-for-courses/timetables?actionId=supp'},'type':'links' },
                 { 'text' : 'Class List','a_attr' : {'href':'courseRegistration/reports/class-list'},'type':'links' },
                { 'text' : 'Exam List','a_attr' : {'href':'courseRegistration/reports/exam-list'},'type':'links' },
            ]
        }
     ]",
    "[
        {
            'text' : 'Reports','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Full Class List','a_attr' : {'href':'courseRegistration/full-class-list'},'type':'links' },
                { 'text' : 'Full Exam List','a_attr' : {'href':'courseRegistration/full-exam-list'},'type':'links' },
                { 'text' : 'Student Course Registration','a_attr' : {'href':'courseRegistration/cr-course-registration/courses'},'type':'links' },
                { 'text' : 'Course Registration Analysis','a_attr' : {'href':'courseRegistration/prog-student-analysis'},'type':'links' },
            ]}
        ]
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
