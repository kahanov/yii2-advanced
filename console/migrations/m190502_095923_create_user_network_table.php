<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_network}}`.
 */
class m190502_095923_create_user_network_table extends Migration
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
        $this->createTable('{{%user_network}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'identity' => $this->string()->notNull(),
            'network' => $this->string(16)->notNull(),
        ], $tableOptions);
        $this->createIndex('{{%idx-user_network-identity-name}}', '{{%user_network}}', ['identity', 'network'], true);
        $this->createIndex('{{%idx-user_network-user_id}}', '{{%user_network}}', 'user_id');
        $this->addForeignKey('{{%fk-user_network-user_id}}', '{{%user_network}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_network}}');
    }
}
