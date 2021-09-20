<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%street}}`.
 */
class m200319_093332_create_street_table extends Migration
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
        $this->createTable('street', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-street-name_unique', 'street', ['city_id', 'name'], true);
        $this->createIndex('idx-street-city_id', 'street', 'city_id');
        $this->addForeignKey('fk-street-city_id', 'street', 'city_id', 'city', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('street');
    }
}
