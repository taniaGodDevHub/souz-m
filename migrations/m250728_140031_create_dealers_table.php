<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dealers}}`.
 */
class m250728_140031_create_dealers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dealers}}', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'address' => $this->string(),
            'phone' => $this->string(),
            'email' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dealers}}');
    }
}
