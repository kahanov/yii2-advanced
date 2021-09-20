<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_photo}}`.
 */
class m190515_102618_create_article_photo_table extends Migration
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
        $this->createTable('article_photo', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-article_photo-article_id', 'article_photo', 'article_id');
        $this->addForeignKey('fk-article_photo-article_id', 'article_photo', 'article_id', 'article', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article_photo');
    }
}
