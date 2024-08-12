<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @updatedAt 2/6/2024 11 am
 */

use app\assets\JstreeAsset;
use yii\helpers\Html;

JstreeAsset::register($this);
$this->title = 'smis - student registration';
?>
    <div class="container courseRegistration-default-index">

        <header class="pb-1 mb-4 border-bottom fs-4 d-flex flex-col text-primary">
            <?= Html::a(
                '<span><i class="bi bi-house fs-4"></i> Home</span>',
                ['/'],
                ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
            )
            ?> >
            <?= Html::a(
                '<span><i class="bi bi-card-heading fs-4"></i> Student Registration </span>',
                ['/student-registration'],
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
        </div>
    </div>

<?php
$jsonMenu = [
    "[
        {
            'text' : 'Uploaded documents','a_attr':{'href':'javascript:void()'},
            'children' : [
                { 'text' : 'Uploaded Documents','a_attr' : {'href':'student-registration/documents'},'type':'links' },
                { 'text' : 'Registered Students','a_attr' : {'href':'student-registration/students'},'type':'links' },
                { 'text' : 'Registration Documents','a_attr' : {'href':'student-registration/registration-documents'},'type':'links' },
            ]
        }
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
