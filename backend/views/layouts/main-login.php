<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use backend\assets\AppAsset;
use backend\assets\LoginAsset;
use yii\helpers\Html;

AppAsset::register($this);
LoginAsset::register($this);
?>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="login">
    <?php $this->beginBody(); ?>
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <?= $content ?>
            </div>
        </div>
    </div>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>
