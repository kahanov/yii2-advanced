<?php

namespace common\components;

use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        // вывод в месте вызова
        $event->translatedMessage = $event->message;

        //$event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
        // или писать лог
    }
}
