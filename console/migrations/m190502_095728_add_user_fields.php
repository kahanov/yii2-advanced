<?php

use yii\db\Migration;

/**
 * Class m190502_095728_add_user_fields
 */
class m190502_095728_add_user_fields extends Migration
{

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->addColumn('user', 'email_confirm_token', $this->string()->unique()->after('email'));
        $this->addColumn('user', 'first_name', $this->string()->null());
        $this->addColumn('user', 'last_name', $this->string()->null());
        $this->addColumn('user', 'avatar', $this->string()->null());
        $this->addColumn('user', 'date_birth', $this->integer()->unsigned()->null());
        $this->addColumn('user', 'facebook', $this->string()->null());
        $this->addColumn('user', 'vk', $this->string()->null());
        $this->addColumn('user', 'ok', $this->string()->null());

        $this->alterColumn('user', 'username', $this->string());
        $this->alterColumn('user', 'password_hash', $this->string());
        $this->alterColumn('user', 'email', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'email_confirm_token');
        $this->dropColumn('user', 'first_name');
        $this->dropColumn('user', 'last_name');
        $this->dropColumn('user', 'avatar');
        $this->dropColumn('user', 'date_birth');
        $this->dropColumn('user', 'operating_time');
        $this->dropColumn('user', 'facebook');
        $this->dropColumn('user', 'vk');
        $this->dropColumn('user', 'ok');

        $this->alterColumn('user', 'username', $this->string()->notNull());
        $this->alterColumn('user', 'password_hash', $this->string()->notNull());
        $this->alterColumn('user', 'email', $this->string()->notNull());
    }
}
