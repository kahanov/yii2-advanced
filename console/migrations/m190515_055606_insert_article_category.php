<?php

use yii\db\Migration;

class m190515_055606_insert_article_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('article_category', ['id' => 1, 'name' => 'Информация', 'slug' => 'informaciya', 'sort' => 1, 'title' => 'Информация']);
        $this->insert('article_category', ['id' => 2, 'name' => 'Новости', 'slug' => 'news', 'sort' => 255, 'title' => 'Новости']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('article_category');
    }
}
