<?php
return [
    'components' => [
        'db' => [
            'class' => 'common\components\MyConnection',
            //'class' => 'yii\db\Connection',
            'driverName' => 'mariadb',
            'schemaMap' => [
                'mariadb' => SamIT\Yii2\MariaDb\Schema::class
            ],
            'dsn' => 'mysql:host=192.168.83.140;dbname=mall',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("SET sql_mode = ''")->execute();
            }
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'messageConfig' => [
                'from' => ['support@example.com' => 'Mall.ru']
            ],
        ],
        /* 'cache' => [
             'class' => 'yii\caching\MemCache',
             'useMemcached' => true,
         ],*/
    ],
];
