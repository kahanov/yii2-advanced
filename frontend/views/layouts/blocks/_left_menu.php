<?php

/* @var $this \yii\web\View */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

?>
<?php
NavBar::begin([
    'options' => [
        'class' => 'left-menu',
    ],
    'innerContainerOptions' => ['class' => 'left-menu__content'],
    'containerOptions' => ['class' => 'left-menu__items'],
]);
$menuItems = [
    ['label' => Yii::t('common', 'Главная'), 'url' => ['/account/default/index']],
    ['label' => Yii::t('common', 'Мой профиль'), 'url' => ['/account/profile/index']],
];
/** @var \common\models\user\User $user */
$user = Yii::$app->user->identity;
?>
<?= Nav::widget([
    'options' => ['class' => 'left-menu__items-container'],
    'items' => $menuItems,
]); ?>
<?php NavBar::end(); ?>
