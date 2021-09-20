<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%metro}}`.
 */
class m190630_083825_create_metro_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('metro', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->unique()->comment("Наименование"),
            'country_id' => $this->integer()->notNull(),
            'region_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
            'coordinate_x' => $this->string()->null(),
            'coordinate_y' => $this->string()->null(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk-metro-country_id', 'metro', 'country_id', 'country', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-metro-region_id', 'metro', 'region_id', 'region', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-metro-city_id', 'metro', 'city_id', 'city', 'id', 'cascade', 'cascade');
        $this->createIndex('idx-metro', 'metro', 'title');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('metro');
    }
}
