<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model common\forms\auth\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('frontend/login', 'Восстановление пароля');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset page">
	<div class="page__content">
		<h1><?= Html::encode($this->title) ?></h1>

		<p><?= Yii::t('frontend/login', 'Пожалуйста, введите ваш email в форму ниже. На него будет отправлена информация для восстановления пароля.')?></p>

		<div class="row">
			<div class="col-lg-5">
				<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
				
				<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

				<div class="form-group">
					<?= Html::submitButton(Yii::t('common', 'Отправить'), ['class' => 'btn btn-primary']) ?>
				</div>
				
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
