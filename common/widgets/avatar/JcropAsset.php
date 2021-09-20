<?php

namespace common\widgets\avatar;

use yii\web\AssetBundle;

class JcropAsset extends AssetBundle
{
	public $sourcePath = '@bower/jcrop/';
	public $js = [
		'js/Jcrop.min.js'
	];
	public $css = [
		'css/Jcrop.min.css'
	];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
