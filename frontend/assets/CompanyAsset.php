<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class CompanyAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/company.css',
    ];
    public $js = [
        'js/phone_button.js',
        'js/company_view.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
