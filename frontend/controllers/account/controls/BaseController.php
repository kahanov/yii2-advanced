<?php

namespace frontend\controllers\account\controls;

use yii\web\Controller;
use yii\filters\AccessControl;

class BaseController extends Controller
{
    public $layout = 'account';

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}
