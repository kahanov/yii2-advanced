<?php

/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\helpers\StringHelper;

?>
<div class="header__top">
    <div class="container">
        <div class="header__row">
            <div class="header__col header__left-container">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_city"
                   class="header__top-link header__top-menu-item header__geo-link">
                    <?php
                    $linkText = Yii::t('common/ad', 'Выберите регион');
                    /** @var \common\models\geo\Region $region */
                    $region = Yii::$app->getUrlManager()->region;
                    if (!empty($region)) {
                        $linkText = $region->title;
                    }
                    ?>
                    <?= StringHelper::truncate($linkText, 19) ?>
                </a>
            </div>
            <?php if (!Yii::$app->devicedetect->isMobile()): ?>
                <div class="header__col header__center-container">
                    <div class="header__top-menu">
                        <?= Html::a(Yii::t('common', 'Статьи'), Yii::$app->urlManager->createAbsoluteUrl(['/article/index']), ['class' => 'header__top-link header__top-menu-item']) ?>
                        <?= Html::a(Yii::t('common', 'О компании'), Yii::$app->urlManager->createAbsoluteUrl(['/site/about']), ['class' => 'header__top-link header__top-menu-item']) ?>
                        <?= Html::a(Yii::t('common', 'Контакты'), Yii::$app->urlManager->createAbsoluteUrl(['/contact/index']), ['class' => 'header__top-link header__top-menu-item']) ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="header__col header__right-container-user">
                <div class="header__user">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <div class="header__user-item">
                            <?= Html::a('<span>' . Yii::t('common', 'Вход и регистрация') . '</span>', Yii::$app->urlManager->createAbsoluteUrl(['/auth/auth/login']), ['class' => 'header__user-login header__link header__top-menu-item']) ?>
                        </div>
                    <?php else: ?>
                        <?php if (!Yii::$app->devicedetect->isMobile()): ?>
                            <div class="header__user-item">
                                <?= Html::a('<span>' . Yii::t('common', 'Личный кабинет') . '</span>', Yii::$app->urlManager->createAbsoluteUrl(['/account/default/index']), ['class' => 'header__user-login header__link header__top-menu-item']) ?>
                            </div>
                        <?php endif; ?>
                        <div class="header__user-item">
                            <?= Html::a('<span>' . Yii::t('common', 'Выйти') . '</span>', Yii::$app->urlManager->createAbsoluteUrl(['/auth/auth/logout']), ['class' => 'header__user-logout header__link header__top-menu-item', 'data-method' => 'post']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
