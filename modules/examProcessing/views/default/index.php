<?php

use app\assets\JstreeAsset;
use yii\helpers\Html;

JstreeAsset::register($this);
?>
    <div class="container examProcessing-default-index">

        <header class="pb-1 mb-4 border-bottom fs-4 d-flex flex-col text-primary">
            <?= Html::a(
                '<span><i class="bi bi-house fs-4"></i> Home</span>',
                ['/'],
                ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
            )
            ?>
            >
            <?= Html::a(
                '<span><i class="bi bi-clipboard-data-fill fs-4"></i>  ' . $this->title . ' </span>',
                '#',
                ['class' => "d-flex align-items-center text-decoration-none text-primary mx-3 flex-grow"]
            )
            ?>
        </header>
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-2 menu-container">
            <div class="col menu-tree-container0"></div>
            <div class="col menu-tree-container1"></div>
            <div class="col menu-tree-container2"></div>
            <div class="col menu-tree-container3"></div>
        </div>
    </div>
<?php
$jsonMenu = "
[
    {'text' : 'Examinations','a_attr':{'href':'javascript:void()'},
    'children' : [
        { 'text' : 'Process results','a_attr' : {'href':'/exam-processing/process-results'},'type':'links' },
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
        $("#menu-search-input").val() ? clearBtn.show() : clearBtn.hide();
    });
    clearBtn.on('click',function () {
        $('[class*="menu-tree-container"]').jstree("clear_search");
        $("#menu-search-input").val('').trigger('change');
    });
JS
);

$m = [$jsonMenu];

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
