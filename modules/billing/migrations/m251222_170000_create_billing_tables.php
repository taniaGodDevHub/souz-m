<?php

use yii\db\Migration;

/**
 * Миграция для создания таблиц биллинговой системы
 */
class m251222_170000_create_billing_tables extends Migration
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

        // 1. Создание таблицы transaction_type
        $this->createTable('{{%transaction_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ], $tableOptions);

        // Заполнение предустановленными типами транзакций
        $this->batchInsert('{{%transaction_type}}', ['name'], [
            ['Выплата аваркому'],
            ['Выплата менеджеру'],
            ['Возврат средств'],
            ['Комиссия'],
            ['Перевод между счетами'],
            ['Пополнение счета'],
            ['Списание со счета'],
            ['Оплата услуг'],
            ['Оплата от клиента'],
            ['Штраф'],
            ['Бонус'],
        ]);

        // 2. Создание таблицы account
        $this->createTable('{{%account}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'project_id' => $this->integer()->null(),
            'name' => $this->string(255)->notNull(),
        ], $tableOptions);

        // Индексы для account
        $this->createIndex('idx_account_user', '{{%account}}', 'user_id');
        $this->createIndex('idx_account_project', '{{%account}}', 'project_id');

        // Внешние ключи для account (если таблицы user и project существуют)
        // Проверяем существование таблицы user
        $userTableExists = $this->db->schema->getTableSchema('{{%user}}') !== null;
        if ($userTableExists) {
            $this->addForeignKey(
                'fk_account_user',
                '{{%account}}',
                'user_id',
                '{{%user}}',
                'id',
                'SET NULL',
                'CASCADE'
            );
        }

        // 3. Создание таблицы transaction
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'from_acc_id' => $this->integer()->null(),
            'to_acc_id' => $this->integer()->null(),
            'transaction_type' => $this->integer()->notNull(),
            'amount' => $this->bigInteger()->notNull()->comment('Сумма в копейках'),
            'date_add' => $this->integer()->notNull(),
        ], $tableOptions);

        // Индексы для transaction
        $this->createIndex('idx_transaction_from', '{{%transaction}}', 'from_acc_id');
        $this->createIndex('idx_transaction_to', '{{%transaction}}', 'to_acc_id');
        $this->createIndex('idx_transaction_type', '{{%transaction}}', 'transaction_type');
        $this->createIndex('idx_transaction_date', '{{%transaction}}', 'date_add');

        // Внешние ключи для transaction
        $this->addForeignKey(
            'fk_transaction_from',
            '{{%transaction}}',
            'from_acc_id',
            '{{%account}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_transaction_to',
            '{{%transaction}}',
            'to_acc_id',
            '{{%account}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_transaction_type',
            '{{%transaction}}',
            'transaction_type',
            '{{%transaction_type}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // 4. Создание системных счетов
        $this->insert('{{%account}}', [
            'user_id' => null,
            'project_id' => null,
            'name' => 'Системный счет',
        ]);

        $this->insert('{{%account}}', [
            'user_id' => null,
            'project_id' => null,
            'name' => 'Счет для выплат',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаление таблиц в обратном порядке
        $this->dropTable('{{%transaction}}');
        $this->dropTable('{{%account}}');
        $this->dropTable('{{%transaction_type}}');
    }
}

