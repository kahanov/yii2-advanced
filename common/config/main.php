<?php
$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'name' => 'Mall.ru',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => 'ru-RU',
    'bootstrap' => [
        'common\bootstrap\SetUp',
        'params',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\DummyCache',
            //'class' => 'yii\caching\MemCache',
            //'useMemcached' => true,

            //'class' => 'yii\caching\FileCache',
            //'cachePath' => '@common/runtime/cache',
        ],
        'dc' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'i18n' => [
            'translations' => [
                'frontend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'backend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'on missingTranslation' => ['common\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
            ],
        ],
        'priceFormatter' => [
            'class' => 'yii\i18n\Formatter',
            'numberFormatterOptions' => [
                \NumberFormatter::MIN_FRACTION_DIGITS => 0,
                \NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
            'currencyCode' => 'RUR',
            'thousandSeparator' => ' ',
            'numberFormatterSymbols' => [\NumberFormatter::CURRENCY_SYMBOL => '₽'],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',  //GD or Imagick
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=' . $params['sphinx_host'] . ';port=9306;',
            'username' => 'root',
            'password' => '',
        ],
        'params' => [
            'class' => 'common\components\Params',
        ],
    ],
    'modules' => [],
];
