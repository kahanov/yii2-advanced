<?php
return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['backendHostInfo'],
	//'baseUrl' => $params['backendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'geo' => 'geo/default/index',

        /*'' => 'site/index',
        '<_a:login|logout>' => 'auth/<_a>',

        'users-admin' => 'users-admin/assignment/index',
        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',*/
    ],
];
