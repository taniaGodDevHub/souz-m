<?php

use yii\db\Migration;

/**
 * Создаёт таблицу lead_status_history.
 */
class m260204_000004_create_lead_status_history_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%lead_status_history}}', [
            'id' => $this->primaryKey(),
            'status_id' => $this->integer()->notNull()->comment('Статус'),
            'lead_id' => $this->integer()->notNull()->comment('Лид'),
            'date_add' => $this->integer()->null()->comment('Дата добавления'),
        ]);

        $this->addForeignKey(
            'fk-lead_status_history-status_id',
            '{{%lead_status_history}}',
            'status_id',
            '{{%lead_status}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-lead_status_history-lead_id',
            '{{%lead_status_history}}',
            'lead_id',
            '{{%lead}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-lead_status_history-lead_id', '{{%lead_status_history}}');
        $this->dropForeignKey('fk-lead_status_history-status_id', '{{%lead_status_history}}');
        $this->dropTable('{{%lead_status_history}}');
    }
}
