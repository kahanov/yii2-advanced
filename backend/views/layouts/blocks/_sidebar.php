<?php
/* @var $this yii\web\View */

/** @var \common\models\user\User $user */

use yii\helpers\Url;
use yii\helpers\Html;
use backend\widgets\Menu;

$user = Yii::$app->user->identity;
?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">
            <?= Html::a('<i class="fa fa-paw"></i><span>' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'site_title']) ?>
        </div>
        <div class="clearfix"></div>

        <!-- menu prile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="<?= (!empty($user->avatar)) ? $user->getThumbFileUrl('avatar', 'avatar') : '/images/128x128.png' ?>"
                     alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span><?= Yii::t('backend/base', 'Добро пожаловать') ?>,</span>
                <h2><?= $user->username ?></h2>
            </div>
        </div>
        <!-- /menu prile quick info -->

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>------------</h3>
                <?php
                $menu = [
                    ["label" => Yii::t('common', 'Главная'), "url" => Url::to(['/site/index']), "icon" => "home"],
                    [
                        "label" => Yii::t('common', 'Настройки'),
                        "url" => "javascript:void(0);",
                        "icon" => "gear",
                        "items" => [
                            [
                                "label" => Yii::t('common', 'Все параметры'),
                                "url" => Url::to(['/settings/params/index']),
                                'active' => $this->context->id == 'settings/params',
                            ],
                        ],
                    ],
                    [
                        "label" => Yii::t('backend/user', 'Настройки доступа'),
                        "url" => "javascript:void(0);",
                        "icon" => "user-secret",
                        "items" => [
                            [
                                "label" => Yii::t('backend/user', 'Назначения'),
                                "url" => Url::to(['/users-admin']),
                                'active' => $this->context->id == 'users-admin',
                            ],
                            [
                                "label" => Yii::t('backend/user', 'Разрешения'),
                                "url" => Url::to(['/users-admin/permission']),
                                'active' => $this->context->id == 'users-admin/permission',
                            ],
                            [
                                "label" => Yii::t('backend/user', 'Роли'),
                                "url" => Url::to(['/users-admin/role']),
                                'active' => $this->context->id == 'users-admin/role',
                            ],
                            [
                                "label" => Yii::t('backend/user', 'Маршруты'),
                                "url" => Url::to(['/users-admin/route']),
                                'active' => $this->context->id == 'users-admin/route',
                            ],
                        ],
                    ],
                    ['label' => Yii::t('backend/user', 'Пользователи'), 'icon' => 'user', 'url' => Url::to(['/user/index']), 'active' => $this->context->id == 'user'],
                    [
                        "label" => Yii::t('backend/article', 'Статьи'),
                        "url" => "javascript:void(0);",
                        "icon" => "newspaper-o",
                        "items" => [
                            [
                                "label" => Yii::t('backend/article', 'Категории'),
                                "url" => Url::to(['/article/category']),
                                'active' => $this->context->id == 'article/category/index',
                            ],
                            [
                                "label" => Yii::t('backend/article', 'Статьи'),
                                "url" => Url::to(['/article/article/index']),
                                'active' => $this->context->id == 'article/article',
                            ],
                        ],
                    ],
                    [
                        "label" => Yii::t('backend/geo', 'Гео'),
                        "url" => "javascript:void(0);",
                        "icon" => "question-circle",
                        "items" => [
                            [
                                "label" => Yii::t('backend/geo', 'Страны'),
                                "url" => Url::to(['/geo']),
                                'active' => $this->context->id == 'geo',
                            ],
                            [
                                "label" => Yii::t('backend/geo', 'Регионы'),
                                "url" => Url::to(['/geo/region']),
                                'active' => $this->context->id == 'region',
                            ],
                            [
                                "label" => Yii::t('backend/geo', 'Районы'),
                                "url" => Url::to(['/geo/district']),
                                'active' => $this->context->id == 'district',
                            ],
                            [
                                "label" => Yii::t('backend/geo', 'Населенные пункты'),
                                "url" => Url::to(['/geo/city']),
                                'active' => $this->context->id == 'city',
                            ],
                            [
                                "label" => Yii::t('backend/geo', 'Метро'),
                                "url" => Url::to(['/geo/metro']),
                                'active' => $this->context->id == 'metro',
                            ],
                        ],
                    ],
                ];
                $menu = \common\helpers\BaseBackendHelper::checkAdminMenu($menu);
                ?>
                <?= Menu::widget(['items' => $menu]) ?>
            </div>
        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <!--<div class="sidebar-footer hidden-small">
			<a href="<? /*= Url::to(['/settings']) */ ?>" data-toggle="tooltip" data-placement="top"
			   title="<? /*= Yii::t('backend/settings', 'Настройки') */ ?>">
				<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="FullScreen">
				<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="Lock">
				<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
			</a>
			<? /*= Html::a(
				'<span class="glyphicon glyphicon-off" aria-hidden="true"></span>',
				['/auth/logout'],
				[
					'data-method' => 'post',
					'data-toggle' => 'tooltip',
					'data-placement' => 'top',
					'title' => Yii::t('login', 'Выйти')
				]
			) */ ?>
		</div>-->
        <!-- /menu footer buttons -->
    </div>
</div>
