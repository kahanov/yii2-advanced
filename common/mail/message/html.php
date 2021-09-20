<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

?>
<h1 style="margin:0 0 23px 0;color:#121212;font-family:helvetica,arial;font-size:23px;font-weight:700;line-height:30px;text-align:left;">
    Здравствуйте, <?= $data['userToName'] ?>
</h1>

<p style="-webkit-font-smoothing:subpixel-antialiased;margin:10px 0;color:#121212;font-family:helvetica,arial;font-size:15px;line-height:22px;text-align:left">
    Вам было отправлено сообщение от
    пользователя <?= $data['name'] ?>
    .<br>
    <?php if (!empty($data['phone'])) : ?>
        Телефон: <?= $data['phone'] ?>.<br>
    <?php endif; ?>
    E-mail: <?= $data['email'] ?>.<br>
    Текст сообщения:<br>
    <?= $data['message'] ?>
</p>
