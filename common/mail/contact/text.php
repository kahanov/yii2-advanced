<?php
/* @var $this yii\web\View */
/* @var $body string */

$name = $user['name'];
$email = $user['email'];
?>
Здравствуйте!
Вам пришло сообщение от <?= $name ?>, E-mail: <?= $email ?>.
Содержание сообщения:
<?= $body ?>
