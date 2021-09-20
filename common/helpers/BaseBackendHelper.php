<?php

namespace common\helpers;

use backend\modules\yii2_admin\components\Helper;

class BaseBackendHelper
{

    /**
     * @param array $menu
     * @return array
     */
    public static function checkAdminMenu(array $menu): array
    {
        $newMenu = [];
        foreach ($menu as $key => $item) {
            if (!empty($item['items'])) {
                $newMenu[$key] = $item;
                $newMenu[$key]['items'] = self::checkAdminSubMenu($item['items']);
                if (count($newMenu[$key]['items']) <= 0) {
                    unset($newMenu[$key]);
                }
            } else {
                $checkRoute = Helper::checkRoute($item['url']);
                if ($checkRoute) {
                    $newMenu[$key] = $item;
                }
            }
        }
        return $newMenu;
    }

    /**
     * @param array $items
     * @return array
     */
    private static function checkAdminSubMenu(array $items): array
    {
        $newItems = [];
        foreach ($items as $key => $item) {
            if (!empty($item['items'])) {
                $newItems[$key] = $item;
                $newItems[$key]['items'] = self::checkAdminSubMenu($item['items']);
                if (count($newItems[$key]['items']) <= 0) {
                    unset($newItems[$key]);
                }
            } else {
                $checkRoute = Helper::checkRoute($item['url']);
                if ($checkRoute) {
                    $newItems[$key] = $item;
                }
            }
        }
        return $newItems;
    }
}
