<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%region}}`.
 */
class m190502_100774_create_region_table extends Migration
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
        $this->createTable('region', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'v_title' => $this->string()->null(),
            'subdomain' => $this->string()->notNull()->unique(),
            'coordinates' => $this->string()->null(),
        ], $tableOptions);
        $this->addForeignKey('fk-region-country_id', 'region', 'country_id', 'country', 'id', 'cascade', 'cascade');
        $this->createIndex('idx-region', 'region', [
            'title',
            'subdomain',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-region-country_id', 'region');
        $this->dropTable('region');
    }
}
