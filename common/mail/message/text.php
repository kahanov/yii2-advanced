<?php

/* @var $this yii\web\View */
?>
Здравствуйте, <?= $data['userToName'] ?>!
Вам было отправлено сообщение от пользователя <?= $data['name'] ?>.
<?php if (!empty($data['phone'])): ?>
    Телефон: <?= $data['phone'] ?>.
<?php endif; ?>
E-mail: <?= $data['email'] ?>.
Текст сообщения:
<?= $data['message'] ?>
