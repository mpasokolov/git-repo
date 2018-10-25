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
            'username' => $this->string(30)->notNull(),
            'password' => $this->string(30)->notNull(),
            'auth_key' => $this->string(255),
            'access_token' => $this->string(255)
        ]);

        $this->addForeignKey('activity-user', 'activity', 'id_author', 'user', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('activity-user', 'activity');
        $this->dropTable('user');
    }
}
