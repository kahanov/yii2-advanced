<?php

namespace common\components;

use common\models\Config;
use yii\base\Component;
use yii\base\BootstrapInterface;

class Params extends Component implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        try {
            $params = Config::find()->asArray()->all();
        } catch (\Exception $e) {
        }

        if (!empty($params)) {
            foreach ($params as $param) {
                switch ($param['type']) {
                    case 'bool':
                        $paramValue = (bool)$param['value'];
                        break;
                    default:
                        $paramValue = $param['value'];
                }

                \Yii::$app->params[$param['name']] = $paramValue;
            }
        }
    }
}
