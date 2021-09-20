<?php

/* @var $this \yii\web\View */

use yii\helpers\Html;

?>
<footer class="footer">
    <div class="footer__top">
        <div class="footer__container container">
            <div>
                <div class="footer__links-block">
                    <div class="footer__menu">
                        <ul class="footer__menu-list footer__block">
                            <li class="footer__block-title"><?= Yii::t('common', 'Информация') ?></li>
                            <li class="footer__menu-item">
                                <?= Html::a(Yii::t('common', 'Статьи'), Yii::$app->urlManager->createAbsoluteUrl(['article/index']), ['class' => 'footer__link footer__menu-item-link']); ?>
                            </li>
                            <li class="footer__menu-item">
                                <?= Html::a(Yii::t('common', 'Новости'), Yii::$app->urlManager->createAbsoluteUrl(['article/index', 'category_id' => 2]), ['class' => 'footer__link footer__menu-item-link']); ?>
                            </li>
                            <li class="footer__menu-item">
                                <?= Html::a(Yii::t('common', 'Контакты'), Yii::$app->urlManager->createAbsoluteUrl(['/contact/index']), ['class' => 'footer__link footer__menu-item-link']) ?>
                            </li>
                            <li class="footer__menu-item">
                                <?= Html::a(Yii::t('common', 'Информация'), Yii::$app->urlManager->createAbsoluteUrl(['article/index', 'category_id' => 1]), ['class' => 'footer__link footer__menu-item-link']); ?>
                            </li>
                            <li class="footer__menu-item">
                                <?= Html::a(Yii::t('common', 'О компании'), Yii::$app->urlManager->createAbsoluteUrl(['/site/about']), ['class' => 'footer__link footer__menu-item-link']) ?>
                            </li>
                            <li class="footer__menu-item">
                                <?= Html::a(Yii::t('common', 'Политика конфиденциальности'), Yii::$app->urlManager->createAbsoluteUrl(['article/view', 'id' => 132]), ['class' => 'footer__link footer__menu-item-link']) ?>
                            </li>
                        </ul>
                    </div>
                    <div class="footer__menu">
                        <ul class="footer__menu-list footer__block">
                            <li class="footer__block-title">item</li>
                            <li class="footer__menu-item">
                                <a class="footer__link footer__menu-item-link" href="">item</a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer__menu">
                        <ul class="footer__menu-list footer__block">
                            <li class="footer__block-title">item</li>
                            <li class="footer__menu-item">
                                <a class="footer__link footer__menu-item-link" href="">item</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="pull-left">
                &copy; <?= Yii::t('common', 'Портал в России') ?> <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
            </div>
            <div class="pull-right">
                <div class="pull-left" style="margin-right: 40px">
                    <?= Yii::t('common', 'Связаться с нами:') ?>
                    <a href="mailto:info@mall.ru" target=_blank style="color:#fff;">info@mall.ru</a>
                </div>
                <div class="pull-right">
                    счетчик
                </div>
            </div>
        </div>
    </div>
</footer>
