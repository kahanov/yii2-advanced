<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@staticRoot' => $params['staticPath'],
        '@static' => $params['staticHostInfo'],
    ],
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => [
        'log',
        'users-admin',
        'common\bootstrap\SetUp'
    ],
    'modules' => [
        'users-admin' => [
            'class' => 'backend\modules\yii2_admin\Module',
            'layout' => null,
            'controllerMap' => [
                'assignment' => [
                    'class' => 'backend\modules\yii2_admin\controllers\AssignmentController',
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'user' => [
            'identityClass' => 'common\models\user\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'domain' => $params['cookieDomain'],
            ],
            'loginUrl' => ['auth/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => '_session',
            'cookieParams' => [
                'domain' => $params['cookieDomain'],
                'httpOnly' => true,
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:403',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'backendUrlManager' => require __DIR__ . '/urlManager.php',
        'frontendUrlManager' => require __DIR__ . '/../../frontend/config/urlManager.php',
        'urlManager' => function () {
            return Yii::$app->get('backendUrlManager');
        },
    ],
    'as access' => [
       // 'class' => 'yii\filters\AccessControl',
        'class' => 'backend\modules\yii2_admin\components\AccessControl',
        //'except' => ['auth/login', 'site/error'],
        /*'rules' => [
            [
                'allow' => true,
                'roles' => ['admin'],
                //'roles' => ['@'],
            ],
        ],*/
        'allowActions' => [
            // Маршруты модуля пользователей.
            // Логин и так разрешён, но разлогиниться
            // без этой настройки и без настроенных ролей не получится.
            /*'user/*',
            'site/*',
            'users-admin/*',
            'debug/*',
            'gii/*',*/
        ]
    ],
    'params' => $params,
];
