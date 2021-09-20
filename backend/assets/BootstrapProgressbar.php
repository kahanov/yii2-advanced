<?php

namespace backend\assets;

use yii\web\AssetBundle;

class BootstrapProgressbar extends AssetBundle
{
    public $sourcePath = '@bower/gentelella/vendors/bootstrap-progressbar/';
    public $css = [
//        'css/custom.css',
    ];
    public $js = [
        'bootstrap-progressbar.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
