<?php

use yii\db\Migration;

/**
 * Создаёт таблицу lead_status.
 */
class m260204_000001_create_lead_status_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%lead_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%lead_status}}');
    }
}
