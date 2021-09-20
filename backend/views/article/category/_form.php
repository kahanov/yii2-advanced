<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\forms\article\CategoryForm */
/* @var $form \kartik\form\ActiveForm */

?>

<div class="category-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal form-label-left',
            'novalidate' => ''
        ],
        'enableClientValidation' => true,
        'fieldConfig' => [
            'options' => ['class' => 'item form-group'],
            'template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}{hint}</div>',
            'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
            'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12'],
        ],
    ]); ?>
    <?= $form->field($model, 'name')->textInput(['maxLength' => true]) ?>

    <?= $form->
    field($model, 'slug')->
    hint('<a class="my-gray-btn" data-input-text-id="categoryform-name" data-input-slug-id="categoryform-slug" data-id="translit" href="JavaScript:void(0);">' . Yii::t('common', 'Транслитерация') . '</a>')->
    textInput(['maxLength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
