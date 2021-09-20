<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country`.
 */
class m190502_100773_create_country_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('country', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->unique()->comment("Наименование"),
            'v_title' => $this->string()->notNull()->unique()->comment("Название страны в дательном падеже"),
            'slug' => $this->string()->notNull()->unique()->comment("Название транслитом"),
            'phone_code' => $this->string(6)->notNull()->unique()->comment("Телефонный код"),
            'currency_code' => $this->string(10)->notNull()->comment("Код валюты"),
            'currency_name' => $this->string(15)->notNull()->comment("Название валюты"),
            'language' => $this->string(6)->notNull()->comment("Язык"),
            'country_code' => $this->string(4)->notNull()->comment("Код страны"),
            'coordinates' => $this->string()->null(),
        ], $tableOptions);
        $this->createIndex('idx-country', 'country', [
            'title',
            'slug',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('country');
    }
}
