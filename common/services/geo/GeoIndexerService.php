<?php

namespace common\services\geo;

use Yii;

class GeoIndexerService
{

    /**
     * @param string $index
     * @param array $data
     */
    public function index(string $index, array $data): void
    {
        $params = [];
        $sql = Yii::$app->sphinx->getQueryBuilder()
            ->replace(
                $index,
                $data,
                $params
            );
        Yii::$app->sphinx->createCommand($sql, $params)->execute();
    }

    /**
     * @param string $index
     * @param int $id
     * @return mixed
     */
    public static function deleteSphinxIndex(string $index, int $id)
    {
        $params = [];
        $sql = \Yii::$app->sphinx->getQueryBuilder()
            ->delete(
                $index,
                'id =' . $id,
                $params
            );
        return \Yii::$app->sphinx->createCommand($sql, $params)->execute();
    }
}
