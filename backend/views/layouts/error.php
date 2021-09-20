<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::t('backend', 'Панель управления') ?></title>
    <?php $this->head() ?>
</head>
<body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>">
<?php $this->beginBody(); ?>
<div class="container body">

    <div class="main_container">

		<!-- page content -->
		<?= $this->render('blocks/_content', ['content' => $content]) ?>
		<!-- /page content -->
		
    </div>

</div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
