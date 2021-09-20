<?php

namespace backend\assets;

use yii\web\AssetBundle;

class ThemeFormAsset extends AssetBundle
{
    public $sourcePath = '@bower/gentelella/vendors/';

    public $js = [
        'validator/validator.js',
    ];
    public $depends = [
        'backend\assets\ThemeAsset',
    ];
}
