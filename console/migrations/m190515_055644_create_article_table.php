<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%articles}}`.
 */
class m190515_055644_create_article_table extends Migration
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
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'content' => 'MEDIUMTEXT',
            'title' => $this->string(),
            'description' => $this->text(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
            'anons' => 'MEDIUMTEXT',
        ], $tableOptions);
        $this->createIndex('idx-article-slug', 'article', 'slug', true);
        $this->createIndex('idx-article-category_id', 'article', 'category_id');

        $this->addForeignKey('fk-article-category_id', 'article', 'category_id', 'article_category', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
    }
}
