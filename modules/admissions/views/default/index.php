<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/24/2023
 * @time: 8:13 AM
 */

declare(strict_types=1);

/**
 * @var yii\web\View $this
 * @var string $title
 */

use app\assets\JstreeAsset;
use yii\helpers\Html;

JstreeAsset::register($this);
$this->title = $title;
?>

    <div class="container courseRegistration-default-index">
        <header class="pb-1 mb-4 border-bottom fs-4 d-flex flex-col text-primary">
            <?= Html::a('<span><i class="bi bi-house fs-4"></i>Home</span>', ['/'],
                ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
            ) ?> >
            <?= Html::a('<span><i class="bi bi-card-heading fs-4"></i>Admissions </span>', ['/admissions'],
                ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
            ) ?>
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
        {
            'text' : 'Admission setup','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'KUCCPS Programme Map','a_attr' : {'href':'student-records/org-kuccps-prog-map'},'type':'links' },
            ]
        }
     ]",
    "[
        {
            'text' : 'Student Admission',
            'children' : [
                { 'text' : 'Student Search','a_attr' : {'href':'student-records/utility/student-search'},'type':'links' },
                { 'text' : 'Add New Student','a_attr' : {'href':'student-records/student/create'},'type':'links' },
                { 'text' : 'Upload KUCCPS Students','a_attr' : {'href':'student-records/utility/upload-admitted-list'},'type':'links' },
                { 'text' : 'Upload Admitted Students','a_attr' : {'href':'student-records/utility/ndu-upload-admitted-list'},'type':'links' },
                { 'text' : 'Student List','a_attr' : {'href':'student-records/student'},'type':'links' },
                { 'text' : 'Add Existing Students','a_attr' : {'href':'admissions/students/upload-existing'},'type':'links' },
            ]
        }
    ]",
    "[
        {
            'text' : 'Admitted Candidates','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Upload Internally Admitted students','a_attr' : {'href':'student-records/utility/ndu-upload-admitted-list'},'type':'links' },
                { 'text' : 'Upload KUCCPS students','a_attr' : {'href':'student-records/utility/upload-admitted-list'},'type':'links' },
            ]
		}
    ]",
    "[
        {
            'text' : 'Reports','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Admissions Analysis','a_attr' : {'href':'student-records/reports/nominal-admissions-analysis'},'type':'links' },
                { 'text' : 'Admissions Report','a_attr' : {'href':'student-records/admission-report'},'type':'links' },
                { 'text' : 'Uploaded Admitted Students List','a_attr' : {'href':'student-records/reports/admitted-list'},'type':'links' },
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





