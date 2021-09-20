<?php

namespace frontend\widgets\menu\assets;

use yii\web\AssetBundle;

class NavBarAsset extends AssetBundle
{
    public $sourcePath = '@frontend/widgets/menu/dist/';
    public $path = '';
    public $css = [
        'css/navbar.css'
    ];

    public $js = [
        //'js/modernizr.js',
        'js/nav.min.js',
        'js/topbar.js',
        'js/app.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
