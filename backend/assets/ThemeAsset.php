<?php

namespace backend\assets;

use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle
{
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'backend\assets\BootstrapProgressbar',
        'backend\assets\ThemeBuildAsset',
        'backend\assets\ThemeSrcAsset',
    ];
}
