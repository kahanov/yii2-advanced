<?php

/* @var $this yii\web\View */
/* @var $searchForm \frontend\forms\CompanySearchForm */

/* @var $dataProvider yii\data\DataProviderInterface */

use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="panel panel-default search_panel">
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'action' => [''],
            'method' => 'get',
            'id' => 'company-search-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
        ]) ?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($searchForm, 'region_id', ['options' => ['class' => 'form-group input-group input-group-sm']])->
                label(false)->
                widget(\kartik\widgets\Select2::class, [
                    'data' => [],
                    'language' => 'ru',
                    'showToggleAll' => false,
                    'options' => ['placeholder' => Yii::t('common', 'Регион работы...'), 'multiple' => false],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->
                field($searchForm, 'text', ['options' => ['class' => 'form-group input-group input-group-sm']])->
                label(false)->
                textInput([
                    'placeholder' => Yii::t('common', 'Введите текст для поиска'),
                    'focusout' => true,
                ]) ?>
            </div>
            <div class="col-md-1 search-button-no-indent">
                <?= Html::submitButton(Yii::t('common', 'Найти'), ['class' => 'search-submit search-submit--default']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1 class="panel-title"><?= Yii::t('company', 'Список компаний') ?></h1>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
