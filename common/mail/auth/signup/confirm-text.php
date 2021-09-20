<?php
/* @var $this yii\web\View */
/* @var $user \common\models\user\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
?>
Привет <?= $user->username ?>,

Чтобы активировать ваш аккаунт, нажмите на эту ссылку:

<?= $confirmLink ?>
