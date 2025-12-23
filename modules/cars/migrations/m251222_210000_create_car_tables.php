<?php

use yii\db\Migration;

/**
 * Миграция для создания таблиц марок и моделей автомобилей
 */
class m251222_210000_create_car_tables extends Migration
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

        // 1. Создание таблицы car_mark (марки)
        $this->createTable('{{%car_mark}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Название марки (латиница)'),
            'name_cyrillic' => $this->string(255)->comment('Название марки (кириллица)'),
            'is_popular' => $this->boolean()->defaultValue(0)->comment('Популярная марка'),
            'country' => $this->string(100)->comment('Страна'),
            'year_from' => $this->integer()->comment('Год начала производства марки'),
            'year_to' => $this->integer()->comment('Год окончания производства марки'),
            'created_at' => $this->integer()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ], $tableOptions);

        // Индексы для car_mark
        $this->createIndex('idx_car_mark_name', '{{%car_mark}}', 'name');
        $this->createIndex('idx_car_mark_is_popular', '{{%car_mark}}', 'is_popular');
        $this->createIndex('idx_car_mark_country', '{{%car_mark}}', 'country');

        // 2. Создание таблицы car_model (модели)
        $this->createTable('{{%car_model}}', [
            'id' => $this->primaryKey(),
            'mark_id' => $this->integer()->notNull()->comment('ID марки'),
            'name' => $this->string(255)->notNull()->comment('Название модели (латиница)'),
            'name_cyrillic' => $this->string(255)->comment('Название модели (кириллица)'),
            'class' => $this->string(10)->comment('Класс автомобиля'),
            'year_from' => $this->integer()->comment('Год начала производства модели'),
            'year_to' => $this->integer()->comment('Год окончания производства модели'),
            'created_at' => $this->integer()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ], $tableOptions);

        // Индексы для car_model
        $this->createIndex('idx_car_model_mark_id', '{{%car_model}}', 'mark_id');
        $this->createIndex('idx_car_model_name', '{{%car_model}}', 'name');
        $this->createIndex('idx_car_model_class', '{{%car_model}}', 'class');

        // Внешний ключ
        $this->addForeignKey(
            'fk_car_model_mark',
            '{{%car_model}}',
            'mark_id',
            '{{%car_mark}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%car_model}}');
        $this->dropTable('{{%car_mark}}');
    }
}

