<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company}}`.
 */
class m190502_112251_create_company_table extends Migration
{
	/**
	 * @return bool|void
	 * @throws \yii\base\Exception
	 */
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		$this->createTable('company', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),
			'logotype' => $this->string()->null(),
			'phone' => $this->string()->null(),
			'operating_time' => $this->string(1000)->null(),
			'experience' => $this->integer()->null(),
			'description' => $this->text(),
			'website' => $this->string()->null(),
			'skype' => $this->string()->null(),
			'contact_email' => $this->string()->null(),
			'created_at' => $this->integer()->unsigned()->notNull(),
			'updated_at' => $this->integer()->unsigned()->notNull(),
            'country_id' => $this->integer()->notNull(),
            'region_id' => $this->integer()->notNull(),
            'district_id' => $this->integer()->null(),
            'city_id' => $this->integer()->notNull(),
            'street_id' => $this->integer()->null(),
            'house_number' => $this->integer()->null(),
            'address' => $this->string()->notNull(),
            'coordinates' => $this->string()->null(),
		], $tableOptions);

		$this->createIndex('idx-company-user_id', 'company', 'user_id');
        $this->createIndex('idx-company-country_id', 'company', 'country_id');
        $this->createIndex('idx-company-region_id', 'company', 'region_id');
        $this->createIndex('idx-company-district_id', 'company', 'district_id');
        $this->createIndex('idx-company-city_id', 'company', 'city_id');
        $this->createIndex('idx-company-street_id', 'company', 'street_id');
        $this->createIndex('idx-company-logotype', 'company', 'logotype');

        $this->addForeignKey('fk-company-user_id', 'company', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-company-country_id', 'company', 'country_id', 'country', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-company-region_id', 'company', 'region_id', 'region', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-company-district_id', 'company', 'district_id', 'district', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-company-city_id', 'company', 'city_id', 'city', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-company-street_id', 'company', 'street_id', 'street', 'id', 'CASCADE', 'RESTRICT');
    }
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('company');
	}
}
