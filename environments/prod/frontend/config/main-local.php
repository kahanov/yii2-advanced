<?php
$config = [
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vk' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '',
                    'clientSecret' => '',
                    'scope' => ['email']
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'yandex' => [
                    'class' => 'yii\authclient\clients\Yandex',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'mailru' => [
                    'class' => 'frontend\components\MailRuAuthClient',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
            ],
        ]
    ],
];
return $config;
