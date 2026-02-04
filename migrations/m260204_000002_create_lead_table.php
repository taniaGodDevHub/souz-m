<?php

use yii\db\Migration;

/**
 * Создаёт таблицу lead.
 */
class m260204_000002_create_lead_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%lead}}', [
            'id' => $this->primaryKey(),
            'dtp_date' => $this->integer()->null()->comment('Дата ДТП'),
            'city_id' => $this->integer()->null()->comment('Город'),
            'insurance_company_id' => $this->integer()->null()->comment('Страховая компания'),
            'car_number' => $this->string(50)->null()->comment('Номер авто'),
            'client_id' => $this->integer()->null()->comment('Клиент'),
            'report' => $this->text()->null()->comment('Отчёт партнёра'),
            'date_add' => $this->integer()->null()->comment('Дата добавления'),
            'status_id' => $this->integer()->null()->comment('Статус'),
        ]);

        $this->addForeignKey(
            'fk-lead-city_id',
            '{{%lead}}',
            'city_id',
            'city',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-lead-insurance_company_id',
            '{{%lead}}',
            'insurance_company_id',
            '{{%insurance_company}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-lead-client_id',
            '{{%lead}}',
            'client_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-lead-status_id',
            '{{%lead}}',
            'status_id',
            '{{%lead_status}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-lead-status_id', '{{%lead}}');
        $this->dropForeignKey('fk-lead-client_id', '{{%lead}}');
        $this->dropForeignKey('fk-lead-insurance_company_id', '{{%lead}}');
        $this->dropForeignKey('fk-lead-city_id', '{{%lead}}');
        $this->dropTable('{{%lead}}');
    }
}
