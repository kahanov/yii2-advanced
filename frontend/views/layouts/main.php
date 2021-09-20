<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use common\widgets\Alert;
use frontend\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use yii\helpers\Url;

Url::remember();
AppAsset::register($this);

/** @var \frontend\components\DomainUrlManager $urlManager */
$urlManager = Yii::$app->getUrlManager();
/** @var \common\models\geo\Country $country */
$country = $urlManager->country;
$additional = $country->v_title;
/** @var \common\models\geo\Region $region */
if (!empty($region = $urlManager->region)) {
    $additional = $region->v_title;
}
$homeLabel = Yii::t('common', 'Тестовый проект в') . ' ' . $additional;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
    <?= $this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->request->getHostInfo() . '/' . Yii::$app->request->getPathInfo()]) ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Yii::$app->request->getHostInfo() . '/images/favicon.png']) ?>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap <?= (Yii::$app->devicedetect->isMobile()) ? 'mobile-client' : ''?>">
	<?= $this->render('blocks/_header') ?>

	<main class="container">
		<?= Breadcrumbs::widget([
            'homeLink' => [
                'label' => $homeLabel,
                'url' => Yii::$app->getHomeUrl()
            ],
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?= $content ?>
	</main>
    <?= $this->render('blocks/_footer') ?>
</div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
