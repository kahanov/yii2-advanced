<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%district}}`.
 */
class m190502_100775_create_district_table extends Migration
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
        $this->createTable('district', [
            'id' => $this->primaryKey(),
            'region_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);
        $this->addForeignKey('fk-district-region_id', 'district', 'region_id', 'region', 'id', 'cascade', 'cascade');
        $this->createIndex('idx-district', 'district', [
            'title',
            'slug',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-district-region_id', 'district');
        $this->dropTable('district');
    }
}
