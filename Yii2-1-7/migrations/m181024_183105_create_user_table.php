<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m181024_183105_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(30)->notNull()->unique(),
            'password' => $this->string(250)->notNull(),
            'email' => $this->string(100)->notNull()->unique(),
            'auth_key' => $this->string(255),
            'access_token' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
