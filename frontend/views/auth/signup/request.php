<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model common\forms\auth\SignupForm */

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\authclient\widgets\AuthChoice;

$this->title = Yii::t('frontend/login', 'Регистрация на сайте недвижимости nedvrf.ru');
$this->params['breadcrumbs'][] = Yii::t('frontend/login', 'Регистрация');
?>
<div class="site-signup">
    <div class="col-sm-6">
        <div class="well background-white">
            <h2><?= Yii::t('frontend/login', 'Уже зарегистрированы') ?></h2>
            <p><strong><?= Yii::t('frontend/login', 'Авторизуйтесь') ?></strong></p>
            <a href="<?= Html::encode(Url::to(['/login'])) ?>"
               class="btn btn-primary"><?= Yii::t('frontend/login', 'Авторизация') ?></a>
        </div>
        <div class="well background-white">
            <h2><?= Yii::t('frontend/login', 'Регистрация с помощью') ?></h2>
            <?= AuthChoice::widget([
                'baseAuthUrl' => ['auth/network/auth']
            ]); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="well background-white">
            <h2><?= Yii::t('frontend/login', 'Новый пользователь') ?></h2>
            <p><strong><?= Yii::t('frontend/login', 'Регистрируйтесь') ?></strong></p>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>

            <?= Html::activeHiddenInput($model, 'check', [
                'id' => 'check',
                'value' => '',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('frontend/login', 'Зарегистрироваться'), ['class' => 'btn btn-primary', 'name' => 'signup-button', 'onclick' => "document.getElementById('check').value = 'nospam';"]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
