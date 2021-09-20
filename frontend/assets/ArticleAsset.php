<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ArticleAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/article.css',
	];
	public $js = [];
	public $depends = [
		'yii\web\JqueryAsset',
        'frontend\assets\LazySizesAsset',
	];
}
