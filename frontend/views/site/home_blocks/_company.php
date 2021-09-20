<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\BaseCommonHelper;

/* @var $this yii\web\View */
/* @var array $companies */
/* @var $company \common\models\user\Company */

$variantsAd = [Yii::t('common', 'предложение'), Yii::t('common', 'предложения'), Yii::t('common', 'предложений')];
?>
<div class="page home-companies">
    <div class="page__content">
        <h2 class="page__title-home">
            <?= Yii::t('frontend', 'Компании в') . ' ' . $additional ?>
        </h2>
        <div class="page__delimiter"></div>
        <div class="home-companies__container">
            <ul class="home-companies__list">
                <?php foreach ($companies as $company): ?>
                    <?php
                    $name = htmlspecialchars_decode(Html::encode($company->name));
                    $urlManager = Yii::$app->urlManager;
                    $url = $urlManager->createAbsoluteUrl(['/company/view', 'id' => $company->id, 'region_id' => (!empty($company->region_id)) ? $company->region_id : 0]);
                    $avatar = (!empty($company->logotype)) ? $company->getThumbFileUrl('logotype', 'logotype') : NULL;
                    ?>
                    <li class="home-companies__item">
                        <a href="<?= $url ?>" class="home-companies__link">
                            <div class="avatar avatar_type_company">
                                <?php if (!$avatar): ?>
                                    <div class="avatar__image">
                                        <div style="width: 100%; font-size: 11px; color: #121212; left: 50%; position: absolute; top: 50%; transform: translate(-50%, -50%);"><?= Yii::t('common', 'Нет фото') ?></div>
                                    </div>
                                <?php else:; ?>
                                    <img src="" data-src="<?= $avatar ?>" class="lazyload avatar__image"
                                         alt="<?= $name ?>">
                                <?php endif; ?>

                            </div>
                            <div class="home-companies__item-title"><?= $name ?></div>
                            <?php $adTotal = 1 ?>
                            <span class="home-companies__text">
									<?= sprintf('%d %s', $adTotal, BaseCommonHelper::getPlural($variantsAd, $adTotal)) ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?= Html::a(Yii::t('frontend', 'Все компании'), ['/company'], ['class' => 'home__view-all']) ?>
        </div>
    </div>
</div>
