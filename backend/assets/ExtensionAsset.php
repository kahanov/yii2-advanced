<?php

namespace backend\assets;

use yii\web\AssetBundle;

class ExtensionAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/extension.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
