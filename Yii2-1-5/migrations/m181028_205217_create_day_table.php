<?php

use yii\db\Migration;

/**
 * Handles the creation of table `day`.
 */
class m181028_205217_create_day_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('day', [
            'id' => $this->primaryKey(),
            'date' => $this->integer()->notNull()->unique(),
            'weekend_day' => $this->boolean()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('day');
    }
}
