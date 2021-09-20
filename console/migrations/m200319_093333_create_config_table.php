<?php

use yii\db\Migration;

/**
 * Handles the creation for table `config`.
 */
class m200319_093333_create_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('config', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'value' => $this->text()->notNull(),
            'desc' => $this->string(10000)->null(),
            'type' => $this->string(1000)->notNull()->defaultValue('string'),
        ], $tableOptions);

        $this->createIndex('idx-config-name', 'config', 'name');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('config');
    }
}
