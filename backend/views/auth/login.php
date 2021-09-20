<?php

use yii\helpers\Html;
use common\widgets\Alert;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\forms\auth\LoginForm */

$this->title = Yii::t('login', 'Авторизация');

$fieldOptions1 = [
    'options' => ['class' => 'form-group'],
    'inputTemplate' => "{input}"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group'],
    'inputTemplate' => "{input}"
];
?>

<section class="login_content">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Alert::widget() ?>

    <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true]); ?>
    <div>
        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>
    </div>
    <div>
        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
    </div>
    <div>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
    </div>
    <div>
        <?= Html::submitButton(Yii::t('login', 'Войти'), ['class' => 'btn btn-default submit', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>

    <div class="separator">
        <div>
            <h1><i class="fa fa-paw"></i> <?= Html::encode(Yii::$app->name) ?>
            </h1>
            <p>&copy;<?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?></p>
        </div>
    </div>
</section>
