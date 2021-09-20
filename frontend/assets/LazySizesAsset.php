<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class LazySizesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];
    public $js = [
        'js/lazysizes.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
