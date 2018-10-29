<?php

use yii\db\Migration;

/**
 * Handles the creation of table `day`.
 */
class m181028_205218_create_links_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('links', [
            'id' => $this->primaryKey(),
            'id_activity' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'id_day' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('link-activity', 'links', 'id_activity', 'activity', 'id');
        $this->addForeignKey('link-user', 'links', 'id_user', 'user', 'id');
        $this->addForeignKey('link-day', 'links', 'id_day', 'day', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('link-activity', 'link');
        $this->dropForeignKey('link-user', 'link');
        $this->dropForeignKey('link-day', 'link');
        $this->dropTable('day');
    }
}
