<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\assets;

use yii\web\AssetBundle;

/**
 * Yii comes with some js assets under vendor/yiisoft/yii2/assets
 * Combine and minify these files
 */
class AllYiiAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'studentreg/js/all-yii.min.js'
    ];
    public $depends = [
        'yii\bootstrap5\BootstrapPluginAsset'
    ];
}
