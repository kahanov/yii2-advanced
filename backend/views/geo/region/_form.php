<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model \backend\forms\geo\region\RegionForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="region-form">

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

    <?= $form->
    field($model, 'subdomain')->
    hint('<a class="my-gray-btn" data-input-text-id="georegionform-title" data-input-slug-id="georegionform-subdomain" data-id="translit" href="JavaScript:void(0);">' . Yii::t('common', 'Транслитерация') . '</a>')->
    textInput(['maxLength' => true]) ?>

    <div class="ln_solid"></div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
