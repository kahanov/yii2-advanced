<?php

namespace common\widgets\addressForm;

use yii\web\AssetBundle;

class AddressAsset extends AssetBundle
{
	public $js = [
		'js/search-address.js'
	];
	
	public $depends = [
		'yii\web\JqueryAsset',
		'yii\bootstrap\BootstrapAsset',
	];
	
	public function init()
	{
		$this->sourcePath = __DIR__ . "/assets";
		parent::init();
	}
}
