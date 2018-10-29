<?php

use yii\db\Migration;

/**
 * Handles the creation of table `insert`.
 */
class m181029_211533_create_insert_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', [
            'username' => 'admin',
            'password' => \Yii::$app->security->generatePasswordHash('admin'),
            'email' => 'admin@admin.ru',
        ]);

        $this->insert('activity', [
            'title' => 'Тест',
            'start_day' => '1541192400',
            'end_day' => '1541192400',
            'body' => 'Тест'
        ]);

        $this->insert('day', [
            'date' => '1541192400',
            'weekend_day' => false
        ]);

        $this->insert('links', [
            'id_activity' => 1,
            'id_user' => 1,
            'id_day' => 1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('user');
        $this->truncateTable('activity');
        $this->truncateTable('day');
        $this->truncateTable('links');

    }
}
