<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend Account application asset bundle.
 */
class AccountAsset extends AssetBundle
{
	public $css = [
		'css/account.css',
	];
	public $js = [];
	public $depends = [
		'frontend\assets\AppAsset',
	];
}
