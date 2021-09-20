<?php

/* @var $this yii\web\View */
/* @var $body string */

$name = $user['name'];
$email = $user['email'];
?>
<h1 style="margin:0 0 23px 0;color:#121212;font-family:helvetica,arial;font-size:23px;font-weight:700;line-height:30px;text-align:left;">
    Здравствуйте!
</h1>

<p style="-webkit-font-smoothing:subpixel-antialiased;margin:10px 0;color:#121212;font-family:helvetica,arial;font-size:15px;line-height:22px;text-align:left">
    Вам пришло сообщение от <?= $name ?>, E-mail: <?= $email ?>.
    Содержание сообщения:
</p>

<div style="-webkit-font-smoothing:subpixel-antialiased;margin:30px 0;color:#000;font-family:helvetica,arial;font-size:13px;line-height:18px;text-align:left">
    <?= $body ?>
</div>
