<?php
return [
    'adminEmail' => 'admin@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'user.rememberMeDuration' => 3600 * 24 * 30,
    'cookieDomain' => '.mall.ru',
    'frontendDomains' => ['mall.ru'],
    'frontendHostInfo' => 'https://mall.ru/',
    'backendHostInfo' => 'https://backend.mall.ru',
    'staticHostInfo' => 'https://static.mall.ru',
    'staticPath' => dirname(__DIR__, 2) . '/static',
    'baseDomain' => 'mall.ru',
    'scheme' => 'https://',
    'sphinx_host' => '127.0.0.1',
];
