<?php

namespace common\services;

use yii;

class TransactionManager
{
    /**
     * @param callable $function
     * @throws \Throwable
     */
    public function wrap(callable $function): void
    {
        Yii::$app->db->transaction($function);
    }
}
