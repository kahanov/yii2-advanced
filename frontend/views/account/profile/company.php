<?php
/* @var $this yii\web\View */
/* @var $model \frontend\forms\account\profile\CompanyForm */
/* @var $form \kartik\form\ActiveForm */
/* @var $user \common\models\user\User */

/* @var $company \common\models\user\Company */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\helpers\UserHelper;
use kartik\switchinput\SwitchInput;
use kartik\widgets\TimePicker;
use common\components\phone\PhoneInput;
?>

<?php $form = ActiveForm::begin([
    'id' => 'profile-form',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'fieldConfig' => [
        'hintType' => \kartik\form\ActiveField::HINT_SPECIAL,
        'hintSettings' => ['placement' => 'right', 'onLabelClick' => true, 'onLabelHover' => false]
    ],
]); ?>

<?=
$form->
field($model, 'profile_type', ['options' => ['class' => 'form-group profile_type']])->
radioButtonGroup(
    [
        '1' => Yii::t('common', 'Физическое лицо'),
        '2' => Yii::t('common', 'Организация или ИП'),
    ],
    ['onchange' => "var type = $('#profile-form input[name=profile_type]:checked').val(), url = '/account/profile?profile_type=' + type;window.location.href = url;"]
) ?>

<?= $form->
field($model, 'name', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
])->hint(Yii::t('common', 'Название организации, как в свидетельстве гос. регистрации с сокращением ОПФ')) ?>

<?= $form->field($model, 'logotype')->widget(\common\widgets\avatar\Avatar::class, [
    'uploadParameter' => 'logotype',
    'uploadUrl' => Url::to('/account/profile/logotype'),
    'avatarUrl' => (!empty($company->logotype)) ? $company->getThumbFileUrl('logotype', 'logotype') . '?' . md5(uniqid(rand(), true)) : NULL,
]) ?>

<div class="form-group highlight-addon field-userprofileform-operating_time">
    <label class="control-label has-star col-md-2" for="userprofileform-operating_time">
        <?= Yii::t('common', 'Время работы') ?>
    </label>
    <div class="col-md-6" style="margin-left: 14px;">
        <?= SwitchInput::widget([
            'name' => 'operating_time[calls]',
            'value' => (!empty($model->operating_time['calls'])) ? $model->operating_time['calls'] : 0,
            'pluginOptions' => [
                'onText' => Yii::t('common', 'Да'),
                'offText' => Yii::t('common', 'Нет'),
                'labelText' => Yii::t('account', 'Принимаю звонки')
            ],
            'pluginEvents' => [
                'switchChange.bootstrapSwitch' => 'function(event, state) {
						if (state) {
							$(".timepicker").removeClass("hide");
							$(".checkbox").removeClass("hide");
						} else {
							$(".timepicker").addClass("hide");
							$(".checkbox").addClass("hide");
						}
					}',
            ],
        ]) ?>
        <?php
        $hide = (!empty($model->operating_time) && empty($model->operating_time['calls'])) ? 'hide' : '';
        ?>
        <div class="timepicker <?= $hide ?>">
				<span class="timepicker__wrapper">
					<span class="timepicker__time">
						<?= $form->field($model, 'operating_time[calls_from]')->
                        label(Yii::t('account', 'с'))->
                        widget(TimePicker::class, [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'showSeconds' => false,
                                'showMeridian' => false,
                            ],
                            'options' => [
                                'value' => (!empty($model->operating_time['calls_from'])) ? $model->operating_time['calls_from'] : '09:00',
                                'autocomplete' => 'off',
                                'aria-invalid' => 'false',
                            ],
                        ]); ?>
					</span>
				</span>
            <span class="timepicker__wrapper">
					<span class="timepicker__time">
						<?= $form->field($model, 'operating_time[calls_to]')->
                        label(Yii::t('account', 'до'))->
                        widget(TimePicker::class, [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'showSeconds' => false,
                                'showMeridian' => false,
                            ],
                            'options' => [
                                'value' => (!empty($model->operating_time['calls_to'])) ? $model->operating_time['calls_to'] : '19:00',
                                'autocomplete' => 'off',
                                'aria-invalid' => 'false',
                            ],
                        ]); ?>
					</span>
				</span>
        </div>
        <div class="checkbox <?= $hide ?>">
            <?= Html::checkbox('RealtorForm[operating_time][only]',
                (!empty($model->operating_time['only'])) ? $model->operating_time['only'] : false,
                [
                    'labelOptions' => ['class' => 'has-star'],
                    'label' => Yii::t('account', 'Только в рабочие дни'),
                ]); ?>
            <div class="hint"><?= Yii::t('account', 'После 19:00 мы предложим пользователю написать сообщение') ?></div>
        </div>
        <div class="help-block"></div>
    </div>
</div>


<div class="form-group highlight-addon field-address required">
    <label class="control-label has-star col-md-2" for="address"><?= Yii::t('common', 'Адрес') ?></label>
    <div class="col-md-10 col-md-10">
        <?= \common\widgets\addressForm\AddressMap::widget([
            'model' => $model,
            'form' => $form,
            'mapHide' => NULL,
        ]) ?>
    </div>
</div>

<?= $form->
field($model, 'experience')->
dropDownList(
    UserHelper::inputExperienceList(),
    [
        'prompt' => Yii::t('common', 'Выбор...'),
        'class' => 'form-control input-weight-auto'
    ]
) ?>

<?= $form->
field($model, 'phone')->
widget(PhoneInput::class); ?>

<?= $form->
field($model, 'description', ['wrapperOptions' => ['class' => 'col-md-10']])->
textarea([
    'rows' => 6,
    'maxLength' => 3000
]) ?>

<?= $form->
field($model, 'website', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
]) ?>

<?= $form->
field($model, 'skype', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
]) ?>

<?= $form->
field($model, 'contact_email', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
]) ?>

<div class="form-group">
    <div class="col-md-6 col-md-offset-3">
        <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

