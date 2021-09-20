<?php

namespace common\widgets\maps;

use yii\web\AssetBundle;

class AddressSearchAsset extends AssetBundle
{
	public function init()
	{
		$this->sourcePath = __DIR__ . "/assets";
		parent::init();
	}
}
