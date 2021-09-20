<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model \backend\forms\geo\metro\MetroForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $metro \common\models\geo\Metro */

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
    if (isset($metro)) {
        $url = Url::to([$action, 'id' => $metro->id]);
    } else {
        $url = Url::to([$action, 'id' => $metro->id, 'region_id' => (!empty($model->region_id)) ? $model->region_id : NULL, 'city_id' => (!empty($model->city_id)) ? $model->city_id : NULL]);
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
            'placeholder' => Yii::t('backend/geo', 'Выберите регион...'),
            'depends' => ['geometroform-country_id'],
            'url' => $url,
            'loadingText' => Yii::t('backend/geo', 'Загрузка...'),
        ]
    ]); ?>

    <?= $form->field($model, 'city_id')->
    widget(DepDrop::class, [
        'data' => [],
        'options' => ['placeholder' => Yii::t('backend/geo', 'Выберите город...')],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
        'pluginOptions' => [
            'initialize' => true,
            'placeholder' => Yii::t('backend/geo', 'Выберите город...'),
            'depends' => ['geometroform-region_id'],
            'url' => $url,
            'loadingText' => Yii::t('backend/geo', 'Загрузка...'),
        ]
    ]); ?>

    <?= $form->
    field($model, 'coordinate_x')->
    textInput(['maxLength' => true]) ?>

    <?= $form->
    field($model, 'coordinate_y')->
    textInput(['maxLength' => true]) ?>

    <?= $form->
    field($model, 'sort')->
    textInput(['maxLength' => true]) ?>

    <div class="ln_solid"></div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
