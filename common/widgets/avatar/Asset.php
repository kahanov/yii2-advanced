<?php

namespace common\widgets\avatar;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = __DIR__ . "/assets";

    public $css = [
        'css/avatar.css'
    ];

    public $js = [
        'js/avatar.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'common\widgets\avatar\JcropAsset',
        'common\widgets\avatar\SimpleAjaxUploaderAsset',
    ];
}
