<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model \backend\forms\geo\district\DistrictForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="district-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal form-label-left',
            'novalidate' => ''
        ],
        'enableClientValidation' => true,
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

    <?= $form->field($model, 'region_id')->
    widget(Select2::class, [
        'data' => $model->getRegionList(),
        'language' => 'ru',
        'showToggleAll' => false,
        'options' => ['placeholder' => Yii::t('backend/geo', 'Выберите регион...'), 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]); ?>

    <?= $form->
    field($model, 'slug')->
    hint('<a class="my-gray-btn" data-input-text-id="geodistrictform-title" data-input-slug-id="geodistrictform-slug" data-id="translit" href="JavaScript:void(0);">' . Yii::t('common', 'Транслитерация') . '</a>')->
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

    <div class="ln_solid"></div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
