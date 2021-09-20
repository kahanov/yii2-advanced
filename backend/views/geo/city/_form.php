<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model \backend\forms\geo\city\CityForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $city \common\models\geo\City */

?>

<div class="city-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal form-label-left',
            'novalidate' => ''
        ],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'fieldConfig' => [
            'options' => ['class' => 'item form-group'],
            'template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{hint}{error}</div>',
            'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
            'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12'],
        ],
    ]); ?>

    <?= $form->
    field($model, 'title')->
    textInput(['maxLength' => true]) ?>

    <?= $form->
    field($model, 'v_title')->
    textInput(['maxLength' => true]) ?>

    <?= $form->field($model, 'country_id')->
    widget(Select2::class, [
        'data' => $model->getCountryList(),
        'language' => 'ru',
        'showToggleAll' => false,
        'options' => ['placeholder' => Yii::t('backend/geo', 'Выберите страну...'), 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]); ?>

    <?php
    $action = Yii::$app->controller->action->id;
    if (isset($city)) {
        $url = Url::to([$action, 'id' => $city->id]);
    } else {
        $url = Url::to([$action, 'region_id' => (!empty($model->region_id)) ? $model->region_id : NULL, 'district_id' => (!empty($model->district_id)) ? $model->district_id : NULL]);
    }
    ?>

    <?= $form->field($model, 'region_id')->
    widget(DepDrop::class, [
        'data' => [],
        'options' => ['placeholder' => Yii::t('backend/geo', 'Выберите регион...')],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
        'pluginOptions' => [
            'initialize' => true,
            //'initDepends' => ['geocityform-country_id'],
            'placeholder' => Yii::t('backend/geo', 'Выберите регион...'),
            'depends' => ['geocityform-country_id'],
            'url' => $url,
            'loadingText' => Yii::t('backend/geo', 'Загрузка...'),
        ]
    ]); ?>

    <?= $form->field($model, 'district_id')->
    widget(DepDrop::class, [
        'data' => [],
        'options' => ['placeholder' => Yii::t('backend/geo', 'Выберите район...')],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
        'pluginOptions' => [
            'initialize' => true,
            //'initDepends' => ['geocityform-region_id'],
            'placeholder' => Yii::t('backend/geo', 'Выберите район...'),
            'depends' => ['geocityform-region_id'],
            'url' => $url,
            'loadingText' => Yii::t('backend/geo', 'Загрузка...'),
        ]
    ]); ?>

    <?= $form->
    field($model, 'main_city_region')->
    widget(SwitchInput::class, [
        'pluginOptions' => [
            'onText' => Yii::t('common', 'Да'),
            'offText' => Yii::t('common', 'Нет'),
        ],
    ]) ?>

    <?= $form->
    field($model, 'slug')->
    hint('<a class="my-gray-btn" data-input-text-id="geocityform-title" data-input-slug-id="geocityform-slug" data-id="translit" href="JavaScript:void(0);">' . Yii::t('common', 'Транслитерация') . '</a>')->
    textInput(['maxLength' => true]) ?>

    <?php $model->slug_prefix = true; ?>
    <?= $form->
    field($model, 'slug_prefix')->
    widget(SwitchInput::class, [
        'pluginOptions' => [
            'onText' => Yii::t('common', 'Да'),
            'offText' => Yii::t('common', 'Нет'),
        ],
    ]) ?>

    <?= $form->
    field($model, 'coordinate_x')->
    textInput(['maxLength' => true]) ?>

    <?= $form->
    field($model, 'coordinate_y')->
    textInput(['maxLength' => true]) ?>

    <div class="ln_solid"></div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
