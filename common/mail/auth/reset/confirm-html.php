<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\models\user\User */

/** @var \frontend\components\DomainUrlManager $urlManager */
$urlManager = Yii::$app->frontendUrlManager;
$urlManager->baseUrl = Yii::$app->params['frontendHostInfo'];
$homeUrl = $urlManager->createAbsoluteUrl(['/site/index']);
$resetLink = $urlManager->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);
$lastName = (!empty($user->last_name)) ? $user->last_name : NULL;
$firstName = (!empty($user->first_name)) ? $user->first_name : NULL;
$name = ($lastName && $firstName) ? $firstName . ' ' . $lastName : $user->username;
?>
<h1 style="margin:0 0 23px 0;color:#121212;font-family:helvetica,arial;font-size:23px;font-weight:700;line-height:30px;text-align:left;">
    Здравствуйте, <?= Html::encode($name) ?>
</h1>

<p style="-webkit-font-smoothing:subpixel-antialiased;margin:10px 0;color:#121212;font-family:helvetica,arial;font-size:15px;line-height:22px;text-align:left">
    Вы воспользовались функцией
    восстановления пароля на
    <a href="<?= $homeUrl ?>" target="_blank" style="color:#39f!important;text-decoration:underline"
       rel=" noopener noreferrer"><span style="color:#39f"><?= $homeUrl ?></span></a>.
    Мы не высылаем вам старый пароль, а
    просим указать новый. Это необходимо
    для
    обеспечения информационной
    безопасности.
</p>

<div style="-webkit-font-smoothing:subpixel-antialiased;margin:30px 0;color:#000;font-family:helvetica,arial;font-size:13px;line-height:18px;text-align:left">
    <a href="<?= $resetLink ?>" target="_blank"
       style="background-color:#2b87db;border:#2b87db 11px solid;border-radius:25px;color:#fff!important;display:inline-block;font-family:helvetica;font-size:14px;font-weight:700;padding:0 15px;text-decoration:none"
       rel=" noopener noreferrer"><span style="color:#fff">Восстановить пароль</span></a>
</div>

<p style="-webkit-font-smoothing:subpixel-antialiased;margin:10px 0;color:#121212;font-family:helvetica,arial;font-size:15px;line-height:22px;text-align:left">
    Если вы не запрашивали
    восстановление
    пароля или уже вспомнили свой старый
    пароль, просто удалите это
    письмо.
</p>
