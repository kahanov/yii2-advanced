<?php

namespace backend\assets;

use yii\web\AssetBundle;

class ThemeBuildAsset extends AssetBundle
{
    public $sourcePath = '@bower/gentelella/build/';

    public $css = [
        'css/custom.min.css',
    ];

    public $js = [
        'js/custom.min.js',
    ];
}
