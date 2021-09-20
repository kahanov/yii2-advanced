<?php

/* @var $this \yii\web\View */

/* @var array $categoryMenuItems */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<nav class="mobile-menu__block">
    <?php if (!empty($categoryMenuItems)): ?>
        <div class="mobile-menu-realty">
            <?= $this->render('__mobile-menu-categories', ['categoryMenuItems' => $categoryMenuItems]) ?>
        </div>
    <?php endif; ?>

    <div class="mobile-menu__title"><?= Yii::t('common', 'Личный кабинет') ?></div>
    <?php if (Yii::$app->user->isGuest): ?>
        <?= Html::a('<div class="mobile-menu__item-icon glyphicon glyphicon-registration-mark"></div>' . Yii::t('common', 'Вход и регистрация'),
            Yii::$app->urlManager->createAbsoluteUrl(['/auth/auth/login']), ['class' => 'mobile-menu__item']); ?>
    <?php else: ?>
        <?= Html::a('<div class="mobile-menu__item-icon glyphicon glyphicon-user"></div>' . Yii::t('common', 'Личный кабинет'),
            Yii::$app->urlManager->createAbsoluteUrl(['/account/default/index']), ['class' => 'mobile-menu__item']); ?>

        <?= Html::a('<div class="mobile-menu__item-icon glyphicon glyphicon-off"></div>' . Yii::t('common', 'Выход'),
            Yii::$app->urlManager->createAbsoluteUrl(['/auth/auth/logout']), ['class' => 'mobile-menu__item', 'data-method' => 'post', 'rel' => 'nofollow']); ?>
    <?php endif; ?>
    <div class="mobile-menu__title"><?= Yii::t('common', 'Информация') ?></div>

    <?= Html::a('<div class="mobile-menu__item-icon glyphicon glyphicon-info-sign"></div>' . Yii::t('common', 'Статьи'),
        Yii::$app->urlManager->createAbsoluteUrl(['/article/index']), ['class' => 'mobile-menu__item']); ?>

    <?= Html::a('<div class="mobile-menu__item-icon fa fa-paper-plane"></div>' . Yii::t('common', 'О компании'),
        Yii::$app->urlManager->createAbsoluteUrl(['/site/about']), ['class' => 'mobile-menu__item']); ?>

    <?= Html::a('<div class="mobile-menu__item-icon glyphicon glyphicon-envelope"></div>' . Yii::t('common', 'Контакты'),
        Yii::$app->urlManager->createAbsoluteUrl(['/contact/index']), ['class' => 'mobile-menu__item']); ?>
</nav>
