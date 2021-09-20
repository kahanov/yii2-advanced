<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model common\forms\auth\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('frontend/login', 'Сброс пароля');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password page">
    <div class="page__content">
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Yii::t('frontend/login', 'Пожалуйста, введите новый пароль в форму ниже.') ?></p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('common', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
