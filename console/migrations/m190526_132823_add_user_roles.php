<?php

use yii\db\Migration;

/**
 * Class m190526_132823_add_user_roles
 */
class m190526_132823_add_user_roles extends Migration
{
	public function safeUp()
	{
		$this->batchInsert('{{%auth_item}}', ['type', 'name', 'description'], [
			[1, 'user', 'User'],
			[1, 'admin', 'Admin'],
            [2, '/*', ''],
            [2, '/auth/*', ''],
            [2, '/auth/login', ''],
            [2, '/site/error', ''],
            [2, 'superadmin', 'все доступно'],
		]);
		
		$this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
			['admin', 'user'],
            ['admin', 'superadmin'],
            ['superadmin', '/*'],
            ['user', '/auth/login'],
            ['user', '/site/error'],
		]);
		
		$this->execute('INSERT INTO {{%auth_assignment}} (item_name, user_id) SELECT \'user\', u.id FROM {{%user}} u ORDER BY u.id');
	}
	
	public function down()
	{
		$this->delete('{{%auth_item}}', ['name' => ['user', 'admin']]);
	}
}
