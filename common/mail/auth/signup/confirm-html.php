<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\models\user\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
?>
<h1 style="margin:0 0 23px 0;color:#121212;font-family:helvetica,arial;font-size:23px;font-weight:700;line-height:30px;text-align:left;">
    Здравствуйте, <?= Html::encode($user->username) ?>
</h1>

<p style="-webkit-font-smoothing:subpixel-antialiased;margin:10px 0;color:#121212;font-family:helvetica,arial;font-size:15px;line-height:22px;text-align:left">
    Для продолжения регистрации вам необходимо подтвердить свой E-mail.
</p>

<div style="-webkit-font-smoothing:subpixel-antialiased;margin:30px 0;color:#000;font-family:helvetica,arial;font-size:13px;line-height:18px;text-align:left">
    <a href="<?= $confirmLink ?>" target="_blank"
       style="background-color:#2b87db;border:#2b87db 11px solid;border-radius:25px;color:#fff!important;display:inline-block;font-family:helvetica;font-size:14px;font-weight:700;padding:0 15px;text-decoration:none"
       rel=" noopener noreferrer"><span style="color:#fff">Подтвердить E-mail</span></a>
</div>

<p style="-webkit-font-smoothing:subpixel-antialiased;margin:10px 0;color:#121212;font-family:helvetica,arial;font-size:15px;line-height:22px;text-align:left">
    Если вы не запрашивали
    регистрацию, просто удалите это
    письмо.
</p>
