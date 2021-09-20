<?php

namespace common\helpers;

use Yii;

class BaseFrontendHelper
{

    /**
     * @param string $param
     * @return string
     */
    static public function pageString($param = 'page'): string
    {
        $page = (int)Yii::$app->request->get($param, 1);
        return $page > 1 ? ' - ' . Yii::t('common', 'Страница') . ' ' . $page : '';
    }

    /**
     * @param $str
     * @return bool|string
     */
    public static function formatSeoDescription($str)
    {
        $str = mb_substr($str, 0, 230, 'UTF-8');
        $str = mb_substr($str, 0, mb_strrpos($str, '.', 0, 'UTF-8'), 'UTF-8');
        return $str . '.';
    }
}
