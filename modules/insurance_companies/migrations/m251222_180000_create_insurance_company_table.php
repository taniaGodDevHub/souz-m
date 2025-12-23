<?php

use yii\db\Migration;

/**
 * Миграция для создания таблицы страховых компаний
 */
class m251222_180000_create_insurance_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci ENGINE=InnoDB';
        }

        // Создание таблицы insurance_company
        $this->createTable('{{%insurance_company}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull()->comment('Полное наименование'),
            'short_name' => $this->string(255)->comment('Краткое наименование'),
            'previous_name' => $this->string(255)->comment('Прежнее наименование'),
            'license_number' => $this->string(100)->comment('Номер Лицензии Минфина'),
            'license_date' => $this->date()->comment('Дата Лицензии Минфина'),
            'rsa_certificate_number' => $this->string(100)->comment('Номер Свидетельства РСА'),
            'rsa_certificate_date' => $this->date()->comment('Дата Свидетельства РСА'),
            'phone_fax' => $this->string(100)->comment('Основной телефон/факс'),
            'email' => $this->string(255)->comment('E-mail общий'),
            'created_at' => $this->integer()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ], $tableOptions);

        // Индексы
        $this->createIndex('idx_insurance_company_full_name', '{{%insurance_company}}', 'full_name');
        $this->createIndex('idx_insurance_company_license_number', '{{%insurance_company}}', 'license_number');
        $this->createIndex('idx_insurance_company_rsa_certificate_number', '{{%insurance_company}}', 'rsa_certificate_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%insurance_company}}');
    }
}

