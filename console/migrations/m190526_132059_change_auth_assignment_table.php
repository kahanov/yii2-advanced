<?php

use yii\db\Migration;

/**
 * Class m190526_132059_change_auth_assignment_table
 */
class m190526_132059_change_auth_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->alterColumn('auth_assignment', 'user_id', $this->integer()->notNull());
	
		$this->addForeignKey('fk-auth_assignment-user_id', 'auth_assignment', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
	{
		$this->dropForeignKey('fk-auth_assignment-user_id', 'auth_assignment');
		
		$this->alterColumn('auth_assignment', 'user_id', $this->string(64)->notNull());
	}
}
