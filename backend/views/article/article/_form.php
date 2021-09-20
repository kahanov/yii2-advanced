<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\file\FileInput;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\forms\article\ArticleForm */
/* @var $form \kartik\form\ActiveForm */
/* @var $article \common\models\article\Article */
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
    <?= $form->field($model, 'category_id')->dropDownList($model->categoriesList(), ['prompt' => '']) ?>
    <?= $form->field($model, 'name')->textInput(['maxLength' => true]) ?>

    <?= $form->
    field($model, 'slug')->
    hint('<a class="my-gray-btn" data-input-text-id="articleform-name" data-input-slug-id="articleform-slug" data-id="translit" href="JavaScript:void(0);">' . Yii::t('common', 'Транслитерация') . '</a>')->
    textInput(['maxLength' => true]) ?>

    <?= $form->
    field($model, 'status')->
    widget(SwitchInput::class, [
        'pluginOptions' => [
            'onText' => Yii::t('backend/import', 'Опубликовать'),
            'offText' => Yii::t('backend/import', 'Черновик'),
        ],
    ]) ?>

    <?php
    $deleteUrl = NULL;
    $photoUrl = [];
    if (!empty($article) && !empty($article->photo)) {
        $deleteUrl = Url::toRoute(['delete-photo', 'articleId' => $article->id]);
        $photoUrl = $article->photo->getThumbFileUrl('file', 'thumb');
    }
    ?>
    <?= $form->field($model, 'photo')->widget(FileInput::class, [
        'options' => [
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'theme' => 'gly',
            'deleteUrl' => $deleteUrl,
            'maxFileCount' => 1,
            'showUpload' => false,
            'overwriteInitial' => true,
            'initialPreview' => $photoUrl,
            'initialPreviewAsData' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'anons')->widget(CKEditor::class) ?>
    <?= $form->field($model, 'content')->widget(CKEditor::class) ?>
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
