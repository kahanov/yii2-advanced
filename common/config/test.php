<?php

use yii\helpers\ReplaceArrayValue;

$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\user\User',
            'identityCookie' => new ReplaceArrayValue(['name' => '_identity', 'httpOnly' => true]),
        ],
    ],
    'params' => $params,
];
