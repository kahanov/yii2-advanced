<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use common\widgets\Alert;
use frontend\assets\AccountAsset;
use yii\helpers\Url;

Url::remember();
AccountAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Yii::$app->request->getHostInfo() . '/images/favicon.png']) ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?= $this->render('blocks/_header') ?>
    <div class="container">
        <main class="main">
            <?= $this->render('blocks/_left_menu') ?>
            <div class="main__content">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </main>
    </div>

</div>

<?= $this->render('blocks/_footer') ?>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
