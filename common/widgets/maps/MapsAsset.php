<?php

namespace common\widgets\maps;

use yii\web\AssetBundle;

class MapsAsset extends AssetBundle
{
	public function init()
	{
		$this->sourcePath = __DIR__ . "/assets";
		parent::init();
	}
}
