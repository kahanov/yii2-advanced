<?php

namespace backend\assets;

use yii\web\AssetBundle;

class ThemeSrcAsset extends AssetBundle
{
    public $sourcePath = '@bower/gentelella/src/js';

    public $js = [
        'helpers/smartresize.js',
    ];
}
