<?php

namespace common\helpers;

use Yii;
use common\models\article\Article;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ArticleHelper
{
    /**
     * @return array
     */
    public static function statusList(): array
    {
        return [
            Article::STATUS_DRAFT => Yii::t('common/article', 'Черновик'),
            Article::STATUS_ACTIVE => Yii::t('common/article', 'Опубликован'),
        ];
    }

    /**
     * @param $status
     * @return string
     * @throws \Exception
     */
    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    /**
     * @param $status
     * @return string
     * @throws \Exception
     */
    public static function statusLabel($status): string
    {
        switch ($status) {
            case Article::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Article::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}
