<?php

use yii\db\Migration;

/**
 * Handles the creation of table `activity`.
 */
class m181024_163918_create_activity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('activity', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'start_day' => $this->integer(10)->notNull(),
            'end_day' => $this->integer(10)->notNull(),
            'is_repeat' => $this->boolean(),
            'is_block' => $this->boolean(),
            'body' => $this->string(255)->notNull(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('activity');
    }
}
