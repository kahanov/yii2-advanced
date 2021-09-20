<?php
/* @var $this yii\web\View */
/* @var $model \frontend\forms\account\profile\UserProfileForm */
/* @var $form \kartik\form\ActiveForm */

/* @var $user \common\models\user\User */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\date\DatePicker;
use common\widgets\addressForm\AddressMap;

?>

<?php $form = ActiveForm::begin([
    'id' => 'profile-form',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
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
field($model, 'first_name', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
]) ?>

<?= $form->
field($model, 'last_name', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
]) ?>

<?= $form->field($model, 'avatar')->widget(\common\widgets\avatar\Avatar::class, [
    'uploadUrl' => Url::to('/account/profile/avatar'),
    'avatarUrl' => (!empty($user->avatar)) ? $user->getThumbFileUrl('avatar', 'avatar') . '?' . md5(uniqid(rand(), true)) : NULL,
]) ?>

<?php
$layoutDatePicker = <<< HTML
		{input}
		{remove}
		{picker}
HTML;
?>

<?= $form->field($model, 'date_birth', ['wrapperOptions' => ['class' => 'col-md-3']])->
widget(DatePicker::class, [
    'type' => DatePicker::TYPE_COMPONENT_APPEND,
    'layout' => $layoutDatePicker,
    'separator' => '-',
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd',
    ],
    'options' => [
        'autocomplete' => 'off',
        'aria-invalid' => 'false',
        'placeholder' => date('Y-m-d')
    ],
]); ?>

<?= $form->
field($model, 'facebook', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
]) ?>

<?= $form->
field($model, 'vk', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
]) ?>

<?= $form->
field($model, 'ok', ['wrapperOptions' => ['class' => 'col-md-5']])->
textInput([
    'maxlength' => 255,
]) ?>

<div class="form-group highlight-addon field-address">
    <label class="control-label has-star col-md-2" for="address">Адрес</label>
    <div class="col-md-10 col-md-10">
        <?= AddressMap::widget([
            'model' => $model,
            'form' => $form,
            'mapHide' => NULL,
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-md-offset-3">
        <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
