<?php

use yii\db\Migration;
use common\components\ImportTrait;

/**
 * Class m190630_103143_insert_data_metro_table
 */
class m190630_103143_insert_metro extends Migration
{
    use ImportTrait;

    /**
     * @return bool|void
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->import('metro', [
            'id',
            'title',
            'country_id',
            'region_id',
            'city_id',
            'coordinate_x',
            'coordinate_y',
            'sort',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->truncateTable('metro');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
