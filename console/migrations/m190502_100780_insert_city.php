<?php

use yii\db\Migration;
use common\components\ImportTrait;

/**
 * Class m190401_084703_insert_city
 */
class m190502_100780_insert_city extends Migration
{
    use ImportTrait;

    /**
     * @return bool|void
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->import('city', [
            'id',
            'country_id',
            'region_id',
            'district_id',
            'title',
            'v_title',
            'slug',
            'coordinate_x',
            'coordinate_y',
            'main_city_region',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->truncateTable('city');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
