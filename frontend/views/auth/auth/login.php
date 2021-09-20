<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model common\forms\auth\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\authclient\widgets\AuthChoice;

$this->title = Yii::t('frontend/login', 'Авторизация');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-6">
        <div class="well background-white">
            <h2><?= Yii::t('frontend/login', 'Новый пользователь')?></h2>
            <p><strong><?= Yii::t('frontend/login', 'Регистрируйтесь')?></strong></p>
            <p>
				<?= Yii::t('frontend/login', 'Зарегистрировавшись вы сможете:')?>
				<?= Yii::t('frontend/login', 'добавлять объявления и т.д.')?>
			</p>
            <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>" class="btn btn-primary"><?= Yii::t('frontend/login', 'Регистрация')?></a>
        </div>
        <div class="well background-white">
            <h2><?= Yii::t('frontend/login', 'Войти с помощью')?></h2>
            <?= AuthChoice::widget([
                'baseAuthUrl' => ['auth/network/auth']
            ]); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="well background-white">
            <h2><?= Yii::t('frontend/login', 'Уже зарегистрированы')?></h2>
            <p><strong><?= Yii::t('frontend/login', 'Авторизуйтесь')?></strong></p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
				<?= Yii::t('frontend/login', 'Забыли пароль?')?> <?= Html::a(Yii::t('frontend/login', 'Восстановить пароль'), ['auth/reset/request']) ?>.
            </div>

            <div>
                <?= Html::submitButton(Yii::t('frontend/login', 'Войти'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
