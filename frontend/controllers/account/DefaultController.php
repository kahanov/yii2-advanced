<?php

namespace frontend\controllers\account;

use frontend\controllers\account\controls\BaseController;

class DefaultController extends BaseController
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
