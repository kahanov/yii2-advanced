<?php

use yii\db\Migration;
use common\components\ImportTrait;

/**
 * Class m190401_082435_insert_region
 */
class m190502_100778_insert_region extends Migration
{
    use ImportTrait;

    /**
     * @return bool|void
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->import('region', [
            'id',
            'country_id',
            'title',
            'v_title',
            'subdomain',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->truncateTable('region');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
