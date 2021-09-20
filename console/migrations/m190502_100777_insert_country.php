<?php

use yii\db\Migration;
use common\components\ImportTrait;

/**
 * Class m190401_081302_insert_country
 */
class m190502_100777_insert_country extends Migration
{
	use ImportTrait;
	
	/**
	 * @return bool|void
	 * @throws \yii\base\ErrorException
	 * @throws \yii\db\Exception
	 */
	public function safeUp()
	{
		$this->import('country', [
			'id',
			'title',
			'v_title',
			'slug',
			'phone_code',
			'currency_code',
			'currency_name',
			'language',
			'country_code',
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->execute('SET FOREIGN_KEY_CHECKS = 0;');
		$this->truncateTable('country');
		$this->execute('SET FOREIGN_KEY_CHECKS = 1;');
	}
}
