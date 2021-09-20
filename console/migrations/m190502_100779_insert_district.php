<?php

use yii\db\Migration;
use common\components\ImportTrait;

/**
 * Class m190401_083916_insert_district
 */
class m190502_100779_insert_district extends Migration
{
    use ImportTrait;

    /**
     * @return bool|void
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->import('district', [
            'id',
            'region_id',
            'title',
            'slug',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->truncateTable('district');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
