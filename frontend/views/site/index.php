<?php

use yii\helpers\Url;
use frontend\assets\HomeAsset;

/* @var $this yii\web\View */
/* @var $articles array */
/* @var $ads */

/** @var \frontend\components\DomainUrlManager $urlManager */
$urlManager = Yii::$app->getUrlManager();
/** @var \common\models\geo\Country $country */
$country = $urlManager->country;
$additional = $country->v_title;
/** @var \common\models\geo\Region $region */
if (!empty($region = $urlManager->region)) {
    $additional = $region->v_title;
}
$this->title = 'title' . ' ' . $additional;
$this->registerMetaTag(['name' => 'description', 'content' => 'description' . ' ' . $additional]);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'keywords' . ', в ' . $additional]);
HomeAsset::register($this);
?>
<div class="home">
    <?php if (!empty($companies)): ?>
        <?= $this->render('home_blocks/_company', ['companies' => $companies, 'additional' => $additional]) ?>
    <?php endif; ?>

	<?php if (!empty($articles)): ?>
		<?= $this->render('home_blocks/_news', ['articles' => $articles]) ?>
	<?php endif; ?>

    <?= $this->render('home_blocks/_static_text', ['additional' => $additional]) ?>
</div>
