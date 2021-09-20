<?php

namespace frontend\controllers;

use yii\web\Controller;

class RobotsController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }
}
