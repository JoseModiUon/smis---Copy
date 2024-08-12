<?php
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Js Tree asset bundle.
 *
 * @author Anthony G <agithaka@uonbi.ac.ke>
 * @since 2.0
 */
class JstreeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/vakata/jstree/dist/';
//    public $basePath = '@vendor/vakata/dist/';
//    public $baseUrl = '@web';
//    public $css = [
//        'css/site.css',
//    ];
//    public $js = [
//    ];
    public $depends = [
        YiiAsset::class,
    ];

    public $cssOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );

    public $css = [
        'themes/default/style.css',
    ];

    public $js = [
        'jstree.js',
    ];

    public $publishOptions = [
        //'forceCopy'=>true,
    ];

//    public $depends = [
//        'yii\web\JqueryAsset',
//    ];
}
