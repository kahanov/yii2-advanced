<?php
return [
    'class' => 'frontend\components\DomainUrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'baseDomains' => $params['frontendDomains'],
    'scheme' => $params['scheme'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => false,
    'normalizer' => [
        'class' => 'yii\web\UrlNormalizer',
        'normalizeTrailingSlash' => true,
        'collapseSlashes' => true,
    ],
    'rules' => [
        '' => 'site/index',
        '<_a:about>' => 'site/<_a>',
        'contact' => 'contact/index',
        'signup' => 'auth/signup/request',
        'signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        'reset' => 'auth/reset/request',
        'reset/<_a:[\w-]+>' => 'auth/reset/<_a>',
        '<_a:login|logout>' => 'auth/auth/<_a>',

        ['pattern' => 'robots', 'route' => 'robots/index', 'suffix' => '.txt'],

        ['class' => 'frontend\urls\ArticleUrlRule'],
        'article' => 'article/index',

        'account' => 'account/default/index',

        'account/<_c:[\w\-]+>' => 'account/<_c>/index',
        'account/<_c:[\w\-]+>/<id:\d+>' => 'account/<_c>/view',
        'account/<_c:[\w\-]+>/<_a:[\w-]+>' => 'account/<_c>/<_a>',
        'account/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => 'account/<_c>/<_a>',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];
