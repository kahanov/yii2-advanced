<?php
/* @var $this yii\web\View */

/** @var \common\models\user\User $user */

use yii\helpers\Url;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
?>
<div class="top_nav">
    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        <img src="<?= (!empty($user->avatar)) ? $user->getThumbFileUrl('avatar', 'avatar') : '/images/128x128.png' ?>"
                             alt=""><?= $user->username ?>
                        <span class="fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <a href="<?= Url::to(['/user', 'id' => $user->id]) ?>"><?= Yii::t('backend/base', 'Профиль') ?></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/settings']) ?>">
                                <span class="badge bg-red pull-right">50%</span>
                                <span><?= Yii::t('backend/settings', 'Настройки') ?></span>
                            </a>
                        </li>
                        <li>
                            <?= Html::a('<i class="fa fa-sign-out pull-right"></i>' . Yii::t('login', 'Выйти'),
                                ['/auth/logout'],
                                ['data-method' => 'post']
                            ) ?>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">2</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a href="" class="dropdown-item" target="_blank">
                            <i class="fa fa-file mr-2"></i>
                            <strong style="color: red"><?= 2 ?></strong> <?= 'Item' ?>
                        </a>

                        <div class="dropdown-divider"></div>
                        <a href="" class="dropdown-item" target="_blank">
                            <i class="fa fa-file mr-2"></i>
                            <strong style="color: red"><?= 2 ?></strong> <?= 'Item' ?>
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
