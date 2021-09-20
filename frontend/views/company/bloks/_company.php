<?php

/* @var $this yii\web\View */

/* @var $company \common\models\user\Company */

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use common\helpers\BaseCommonHelper;

$urlManager = Yii::$app->urlManager;
$url = $urlManager->createAbsoluteUrl(['/company/view', 'id' => $company->id, 'region_id' => (!empty($company->region_id)) ? $company->region_id : 0]);
$avatar = (!empty($company->logotype)) ? $company->getThumbFileUrl('logotype', 'logotype') . '?' . md5(uniqid(rand(), true)) : NULL;
$period = BaseCommonHelper::period($company->created_at);
$variantsAd = [Yii::t('common', 'предложение'), Yii::t('common', 'предложения'), Yii::t('common', 'предложений')];
?>
<tr class="catalog__row">
    <td class="catalog__cell catalog__cell--avatar">
        <a href="<?= $url ?>" class="catalog__cell-content">
			<span class="catalog__avatar-container">
				<div class="avatar avatar_type_company">
					<div class="avatar__image" style="background-image: url(<?= $avatar ?>)">
						<?php if (!$avatar): ?>
                            <div style="width: 100%; font-size: 11px; color: #121212; left: 50%; position: absolute; top: 50%; transform: translate(-50%, -50%);"><?= Yii::t('common', 'Нет фото') ?></div>
                        <?php endif; ?>
					</div>
				</div>
			</span>
        </a>
    </td>
    <td class="catalog__cell catalog__cell--info">
        <a href="<?= $url ?>" class="catalog__cell-content">
            <span class="catalog__name"><?= htmlspecialchars_decode(Html::encode($company->name)) ?></span>
            <?php if (!empty($period)): ?>
                <br>
                <span class="catalog__period"><?= $period ?> <?= Yii::t('company', 'на сайте') ?></span>
            <?php endif; ?>
        </a>
    </td>
    <td class="catalog__cell catalog__cell--description">
        <a href="<?= $url ?>" class="catalog__cell-content">
            <?= Html::encode(StringHelper::truncateWords(strip_tags($company->description), 20)) ?>
        </a>
    </td>
    <td class="catalog__cell catalog__cell--offers">
        <div class="catalog__cell-content ">
            <a class="catalog__cell-button" href="<?= $url ?>">
                <?php $adTotal = 1 ?>
                <span class="catalog__cell-button_text">
					<?= sprintf('%d %s', $adTotal, BaseCommonHelper::getPlural($variantsAd, $adTotal)) ?>
				</span>
            </a>
        </div>
    </td>
</tr>

