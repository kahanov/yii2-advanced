<?php

/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\menu\Nav;
use frontend\widgets\menu\NavBar;

?>
<div class="header__bottom">
    <div class="container">
        <div class="header__row">
            <div class="header__col header__left-container">
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/site/index']) ?>">
                    <span class="header__logo"><?= Html::img(Yii::$app->urlManager->createAbsoluteUrl(["/images/logo.png"]), ['alt' => Yii::$app->name, 'title' => Yii::$app->name, 'class' => 'logo__img']) ?></span>
                    <span class="header__site-name"><?= Yii::$app->name ?></span>
                </a>
            </div>
            <div class="header__col header__right-container">
                <div class="header__bottom-menu-mobile" style="<?= (Yii::$app->devicedetect->isMobile()) ? 'width: auto;' : '' ?>">
                    <button type="button" class="navbar-toggle collapsed header__mobile-menu-btn"
                            style="<?= (Yii::$app->devicedetect->isMobile()) ? 'display: block;' : '' ?>">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <?php if (!Yii::$app->devicedetect->isMobile()): ?>
                    <?php Navbar::begin([
                        'title' => false,
                        'options' => [
                            'navOptions' => [
                                'class' => 'header__bottom-menu navbar-collapse collapse',
                                'data-options' => 'is_hover:true',
                                'id' => 'w0-collapse',
                                'role' => 'navigation',
                            ]
                        ],
                    ]); ?>
                    <?php
                    $menuItems = [
                        [
                            'label' => Yii::t('common', 'Главная'),
                            'url' => Yii::$app->urlManager->createAbsoluteUrl(['/site/index']),
                            'active' => $this->context->id == 'site',
                            'options' => [
                                'class' => 'header__bottom-menu-item'
                            ],
                            'linkOptions' => [
                                'class' => 'header__link'
                            ],
                        ],
                        [
                            'label' => Yii::t('common', 'Компании'),
                            'url' => Yii::$app->urlManager->createAbsoluteUrl(['/company']),
                            'active' => $this->context->id == 'item',
                            'options' => [
                                'class' => 'header__bottom-menu-item'
                            ],
                            'linkOptions' => [
                                'class' => 'header__link'
                            ],
                        ],
                    ];
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-left'],
                        'items' => $menuItems,
                        'encodeLabels' => false
                    ]);

                    Navbar::end();
                    ?>
                <?php endif; ?>
                <div class="header__right-panel">
                    <a class="header__create header__link"
                       href="/">
						<span class="header__create-text">
							<span class="header__create-add">+</span> <?= Yii::t('common', 'Item') ?>
						</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>