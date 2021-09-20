<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\forms\settings\params\ParamsForm */
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
    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
