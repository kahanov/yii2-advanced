<?php

/* @var $this yii\web\View */
/* @var $countryId */

/* @var $tabContent */

use kartik\tabs\TabsX;
use yii\helpers\Url;

?>
<div class="choice-city">
	<?php
	$items = [];
	if (!empty($countries)) {
		/** @var \common\models\geo\Country $country */
		foreach ($countries as $country) {
			$content = '';
			$active = false;
			if ($country->id == $countryId) {
				$content = $tabContent;
				$active = true;
			}
			$items[] = [
				'label' => $country->title,
				'content' => $content,
				'linkOptions' => ['data-url' => Yii::$app->urlManager->createAbsoluteUrl(['/helper/choice-city', 'countryId' => $country->id])],
				'active' => $active
			];
		}
	}
	?>
	
	<?= TabsX::widget([
		'items' => $items,
		'position' => TabsX::POS_ABOVE,
		'encodeLabels' => false,
		'containerOptions' => [
			'data-enable-cache' => false,
			'data-cache-timeout' => '5',
		],
		'pluginEvents' => [
			"tabsX.beforeSend" => "function() { $('.tab-content').find('.tab-pane').empty(); }",
		],
	]); ?>
</div>
