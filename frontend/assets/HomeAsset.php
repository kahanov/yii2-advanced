<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
	public $css = [
		'css/home.css',
	];
	public $js = [];
	public $depends = [
		'frontend\assets\AppAsset',
        'frontend\assets\LazySizesAsset',
	];
}
