<?php
/* @var $this yii\web\View */
/* @var $user \common\models\user\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);
?>
Привет, <?= $user->username ?>,

Нажмите на эту ссылку, чтобы восстановить пароль:

<?= $resetLink ?>
