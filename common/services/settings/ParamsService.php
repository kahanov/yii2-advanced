<?php

namespace common\services\settings;

use backend\forms\settings\params\ParamsForm;
use common\models\Config;
use Yii;
use yii\web\NotFoundHttpException;

class ParamsService
{

    /**
     * @param ParamsForm $paramsForm
     * @param Config|NULL $param
     * @return Config
     */
    public function save(ParamsForm $paramsForm, Config $param = NULL): Config
    {
        if (!$param) {
            $param = new Config();
        }
        $param->name = $paramsForm->name;
        $param->value = $paramsForm->value;
        $param->type = $paramsForm->type;
        $param->desc = $paramsForm->desc;
        if (!$param->save()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось сохранить'));
        }

        return $param;
    }

    /**
     * @param $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove($id): void
    {
        $param = $this->get($id);
        if (!$param->delete()) {
            throw new \RuntimeException(Yii::t('common', 'Не удалось удалить'));
        }
    }

    /**
     * @param $id
     * @return Config
     * @throws NotFoundHttpException
     */
    public function get($id): Config
    {
        if (!$param = Config::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('common', 'Данные не найдены'));
        }
        return $param;
    }
}
