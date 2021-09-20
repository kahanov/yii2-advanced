<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m190502_100776_create_city_table extends Migration
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
		$this->createTable('city', [
			'id' => $this->primaryKey(),
			'country_id' => $this->integer()->notNull(),
			'region_id' => $this->integer()->notNull(),
			'district_id' => $this->integer()->null(),
			'title' => $this->string()->notNull(),
			'v_title' => $this->string()->null(),
			'slug' => $this->string()->null()->unique(),
			'coordinate_x' => $this->string()->null(),
			'coordinate_y' => $this->string()->null(),
			'main_city_region' => $this->boolean()->notNull()->defaultValue(0),
		], $tableOptions);
		$this->addForeignKey('fk-city-country_id', 'city', 'country_id', 'country', 'id', 'cascade', 'cascade');
		$this->addForeignKey('fk-city-region_id', 'city', 'region_id', 'region', 'id', 'cascade', 'cascade');
		$this->addForeignKey('fk-city-district_id', 'city', 'district_id', 'district', 'id', 'cascade', 'cascade');
		$this->createIndex('idx-city', 'city', [
			'title',
			'slug',
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('fk-city-country_id', 'city');
		$this->dropForeignKey('fk-city-region_id', 'city');
		$this->dropForeignKey('fk-city-district_id', 'city');
		$this->dropTable('city');
	}
}
