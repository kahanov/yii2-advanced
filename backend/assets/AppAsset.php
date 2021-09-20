<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/base.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'backend\assets\ThemeAsset',
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\ExtensionAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
