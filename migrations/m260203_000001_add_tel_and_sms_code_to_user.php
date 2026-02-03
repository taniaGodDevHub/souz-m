<?php

use yii\db\Migration;

/**
 * Добавляет телефон и поля для входа по SMS-коду.
 */
class m260203_000001_add_tel_and_sms_code_to_user extends Migration
{
    public function safeUp()
    {
        $table = $this->db->schema->getTableSchema('{{%user}}');

        if (!isset($table->columns['tel'])) {
            // Формат хранения: 7XXXXXXXXXX (11 цифр)
            $this->addColumn('{{%user}}', 'tel', $this->string(11)->null()->comment('Телефон 7XXXXXXXXXX'));
            $this->createIndex('idx-user-tel', '{{%user}}', 'tel', true);
        }

        if (!isset($table->columns['sms_code_hash'])) {
            $this->addColumn('{{%user}}', 'sms_code_hash', $this->string(255)->null()->comment('Хеш одноразового SMS-кода'));
        }

        if (!isset($table->columns['sms_code_sent_at'])) {
            $this->addColumn('{{%user}}', 'sms_code_sent_at', $this->integer()->null()->comment('Unix time отправки SMS-кода'));
        }
    }

    public function safeDown()
    {
        $table = $this->db->schema->getTableSchema('{{%user}}');

        if (isset($table->columns['sms_code_sent_at'])) {
            $this->dropColumn('{{%user}}', 'sms_code_sent_at');
        }

        if (isset($table->columns['sms_code_hash'])) {
            $this->dropColumn('{{%user}}', 'sms_code_hash');
        }

        if (isset($table->columns['tel'])) {
            $this->dropIndex('idx-user-tel', '{{%user}}');
            $this->dropColumn('{{%user}}', 'tel');
        }
    }
}

