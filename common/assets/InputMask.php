<?php

namespace common\assets;

use yii\web\AssetBundle;

class InputMask extends AssetBundle
{
	public $sourcePath = '@bower/inputmask/';
	public $css = [];
	public $js = [
		'dist/min/jquery.inputmask.bundle.min.js',
	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
