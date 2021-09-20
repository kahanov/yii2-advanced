<?php

namespace common\widgets\avatar;

use yii\web\AssetBundle;

class SimpleAjaxUploaderAsset extends AssetBundle
{
	public $sourcePath = '@bower/simple-ajax-uploader/';
	public $js = [
		'SimpleAjaxUploader.min.js'
	];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
