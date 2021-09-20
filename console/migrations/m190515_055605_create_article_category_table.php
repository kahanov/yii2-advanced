<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%articles_category}}`.
 */
class m190515_055605_create_article_category_table extends Migration
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
		$this->createTable('article_category', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'slug' => $this->string()->notNull()->unique(),
			'sort' => $this->integer()->notNull(),
			'title' => $this->string(),
			'description' => $this->text(),
		], $tableOptions);
		$this->createIndex('idx-article_category-slug', 'article_category', 'slug', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article_category');
    }
}
