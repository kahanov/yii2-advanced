<?php

namespace common\bootstrap;

use yii\caching\Cache;
use yii\mail\MailerInterface;
use yii\base\BootstrapInterface;
use common\services\ContactService;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail']
        ]);
    }
}
