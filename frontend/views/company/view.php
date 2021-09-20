<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\BaseCommonHelper;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $company \common\models\user\Company */
/* @var $messageForm \frontend\forms\MessageForm */

\frontend\assets\CompanyAsset::register($this);

$avatar = (!empty($company->logotype)) ? $company->getThumbFileUrl('logotype', 'logotype') . '?' . md5(uniqid(rand(), true)) : NULL;
$period = BaseCommonHelper::period($company->created_at);
$variantsAd = [Yii::t('common', 'предложение'), Yii::t('common', 'предложения'), Yii::t('common', 'предложений')];
$this->title = htmlspecialchars_decode($company->name) . ' - ' . Yii::t('company', 'агентство недвижимости, контакты, объекты агентства недвижимости');
$description = '';
if (!empty($company->description)) {
    $description = \yii\helpers\StringHelper::truncate(Html::encode(strip_tags($company->description)), 250);
} else {//Запрет на индексацию, если нет описания, значит не качественная страница скорей всего.
    $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']);
}
$this->registerMetaTag(['name' => 'description', 'content' => htmlspecialchars_decode($description)]);
?>
<?php
$urlManager = Yii::$app->urlManager;
?>
<div class="company">
    <div class="company__container">
        <div class="company__back">
            <a href="<?= Url::to(['/company']) ?>" class="company__back__link company__link">
                < <?= Yii::t('company', 'Все компании') ?></a>
        </div>
        <div class="company__content">
            <main class="company__main">
                <section class="company__main-container company__container-block">
                    <div class="company__main-content">
                        <div class="company__main-name">
                            <h1><?= htmlspecialchars_decode(Html::encode($company->name)) ?></h1></div>
                        <?php if (!empty($company->experience)): ?>
                            <div class="company__main-info">
							<span class="company__main-info_type">
								<?= Yii::t('company', 'На рынке') ?>:
							</span>
                                <span class="company__main-info_text">
								<?php $experience = \common\helpers\UserHelper::getExperience($company->experience); ?>
                                <?= sprintf(Yii::t('company', 'с %s года '), $experience) ?>
							</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($company->address): ?>
                            <div class="company__main-info">
							<span class="company__main-info_type">
								<?= Yii::t('company', 'Адрес') ?>
                                :
							</span>
                                <span class="company__main-info_text"><?= $company->address ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($company->description)): ?>
                            <div class="company__main-info">
							<span class="company__main-info_type">
								<?= Yii::t('company', 'О компании') ?>:
							</span>
                                <span class="company__main-info_text">
                                <?= Yii::$app->formatter->asHtml($company->description, [
                                    'Attr.AllowedRel' => array('nofollow'),
                                    'HTML.SafeObject' => true,
                                    'Output.FlashCompat' => true,
                                    'HTML.SafeIframe' => true,
                                    'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                                ]) ?>
							</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="company__avatar-content">
                        <div class="avatar">
                            <div class="avatar__image" style="background-image: url(<?= $avatar ?>)">
                                <?php if (!$avatar): ?>
                                    <div style="font-size: 13px; left: 50%; position: absolute; top: 50%; transform: translate(-50%, -50%);"><?= Yii::t('common', 'Нет фото') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($period)): ?>
                            <div class="company__period"><?= $period ?> <?= Yii::t('company', 'на сайте') ?></div>
                        <?php endif; ?>
                    </div>
                </section>
            </main>
            <aside class="company__aside">
                <div class="company__aside-container">
                    <div>
                        <section class="company__section company__container-block">
                            <div>
                                <div class="company__agent_name"><?= htmlspecialchars_decode(Html::encode($company->name)) ?></div>
                                <div class="company__contacts">
                                    <?php if (!empty($company->website)): ?>
                                        <div class="company__field">
                                            <div class="company__field-name">
                                                <?= Yii::t('company', 'Сайт') ?>
                                                :
                                            </div>
                                            <div class="company__field-value">
                                                <a href="<?= Url::to(['/company/redirect', 'id' => $company->id]) ?>"
                                                   target="_blank" rel="nofollow" class="company__link">
                                                    <?= preg_replace("(^https?://)", "", $company->website) ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($company->skype)): ?>
                                        <div class="company__field">
                                            <div class="company__field-name">
                                                <?= Yii::t('company', 'Скайп') ?>
                                                :
                                            </div>
                                            <div class="company__field-value">
                                                <a href="<?= $company->skype ?>" target="_blank" rel="nofollow"
                                                   class="company__link">
                                                    skype.com
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($company->phone)): ?>
                                    <div class="company__phone">
                                        <?= $this->render('../_buttons/_phone_button', [
                                            'phone' => $company->phone,
                                            'type' => 'company',
                                            'id' => $company->id,
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id != $company->user_id): ?>
                                    <div class="company__info-message">
                                        <?= Html::button('<span class="info-message-button-text">' . Yii::t('common', 'Написать сообщение') . '</span>', ['class' => 'info-message-button']) ?>
                                    </div>
                                    <div class="company__info-message-form company_message">
                                        <?php $form = ActiveForm::begin([
                                            'id' => 'company-message-form',
                                            'action' => Url::to(['company/message']),
                                            'type' => ActiveForm::TYPE_HORIZONTAL,
                                            'enableClientValidation' => true,
                                            'fieldConfig' => [
                                                'template' => '<div class="col-md-12">{input}{error}</div>',
                                            ],
                                        ]); ?>
                                        <?= Html::activeHiddenInput($messageForm, 'check', [
                                            'id' => 'check',
                                            'value' => '',
                                        ]) ?>
                                        <?= Html::activeHiddenInput($messageForm, 'company', ['value' => $company->id]) ?>
                                        <div class="company_message__component-container">
                                            <div class="company_message__block">
                                                <div class="company_message__close">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                         viewBox="0 0 14 14">
                                                        <path fill-rule="evenodd"
                                                              d="M7 5.586L2.05.636.636 2.05 5.586 7l-4.95 4.95 1.414 1.414L7 8.414l4.95 4.95 1.414-1.414L8.414 7l4.95-4.95L11.95.636 7 5.586z"></path>
                                                    </svg>
                                                </div>
                                                <div class="company_message__form-block">
                                                    <div class="company_message__detail">
                                                        <div class="company_message__title"><?= Yii::t('common', 'Написать сообщение') ?></div>
                                                    </div>
                                                    <div class="company_message__detail">
                                                        <?= $form->field($messageForm, 'name')->label(false)->textInput([
                                                            'autofocus' => true,
                                                            'placeholder' => Yii::t('common', 'Ваше имя'),
                                                        ]) ?>
                                                    </div>
                                                    <div class="company_message__detail">
                                                        <?= $form->field($messageForm, 'email')->label(false)->textInput([
                                                            'autofocus' => true,
                                                            'placeholder' => Yii::t('common', 'Ваше email'),
                                                        ]) ?>
                                                    </div>
                                                    <div class="company_message__detail">
                                                        <?= $form->
                                                        field($messageForm, 'phone')->
                                                        widget(\common\components\phone\PhoneInput::class, [
                                                            'options' => ['placeholder' => Yii::t('common', 'Ваше номер телефона'),]
                                                        ]); ?>
                                                    </div>
                                                    <div class="company_message__detail">
                                                        <?= $form->
                                                        field($messageForm, 'message', ['options' => ['class' => 'form-group item_message__message']])->
                                                        label(false)->
                                                        textarea([
                                                            'class' => 'form-control item_message__text',
                                                            'rows' => 6,
                                                            'maxlength' => 2000,
                                                            'placeholder' => Yii::t('common', 'Текст сообщения'),
                                                        ]) ?>
                                                    </div>
                                                    <div class="company_message__button">
                                                        <?= Html::submitButton('<span class="company_message__button-text">' . Yii::t('common', 'Отправить') . '</span></span><svg class="button_load" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M8 16a8.008 8.008 0 0 1-8-8h2.134A5.866 5.866 0 1 0 8 2.133V0a8 8 0 1 1 0 16z"></path></svg>', ['class' => 'search-submit item-search-submit search-submit--default company-send-message', 'onclick' => "document.getElementById('check').value = 'nospam';"]) ?>
                                                        <div class="company_message__error popup_component-err popup_component-left"
                                                             style="display: none"><?= Yii::t('common', 'Не удалось отправить. Попробуйте ещё') ?></div>
                                                    </div>
                                                </div>
                                                <div class="company_message__suc-block" style="display: none">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="40"
                                                             height="29" viewBox="0 0 40 29">
                                                            <g fill="none" fill-rule="evenodd">
                                                                <path d="M0 0h40v30H0z"></path>
                                                                <path fill="#2E9E00"
                                                                      d="M16.172 28.704a.992.992 0 0 1-1.41.004L.293 14.095a1.01 1.01 0 0 1 .005-1.425l3.119-3.149a.994.994 0 0 1 1.418 0l10.631 10.736L35.116.294a.983.983 0 0 1 1.399-.006l3.2 3.232a1.01 1.01 0 0 1-.007 1.418L16.172 28.704z"></path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <h3><?= Yii::t('common', 'Сообщение отправлено') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>







