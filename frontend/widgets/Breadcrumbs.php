<?php

namespace frontend\widgets;

use yii\helpers\Html;
use yii\helpers\Url;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    public $options = [
        'class' => 'breadcrumb',
        'itemscope itemtype' => 'http://schema.org/BreadcrumbList'
    ];

    public function init()
    {
        $links = $this->links;
        foreach ($links as $key => &$link) {
            if (is_array($link)) {
                $link['template'] = self::getLiWithSchema($link['label'], $link['url'], $key + 1);
            }
        }
        $this->links = $links;
        parent::init();
    }

    /**
     * @param $label
     * @param $url
     * @param $key
     * @return string
     */
    public static function getLiWithSchema($label, $url, $key): string
    {
        $span = "<span itemprop='name'>{$label}</span><meta itemprop='position' content='{$key}' />";
        $link = Html::a($span, Url::to($url), ['itemprop' => 'item']);
        $li = "<li itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>{$link}</li>";
        return $li;
    }
}
