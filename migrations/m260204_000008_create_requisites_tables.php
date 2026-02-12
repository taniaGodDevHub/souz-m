<?php

use yii\db\Migration;

/**
 * Создаёт таблицы requisites_type и requisites.
 */
class m260204_000008_create_requisites_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%requisites_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->batchInsert('{{%requisites_type}}', ['id', 'name', 'sort'], [
            [1, 'Физическое лицо', 0],
            [2, 'Самозанятый', 1],
            [3, 'Индивидуальный предприниматель', 2],
            [4, 'ООО', 3],
        ]);

        $this->createTable('{{%requisites}}', [
            'id' => $this->primaryKey(),
            'requisites_type_id' => $this->integer()->null()->comment('Правовая форма'),
            'ur_name' => $this->string(500)->null()->comment('Краткое наименование юр. лица'),
            'ur_full_name' => $this->string(1500)->null()->comment('Полное наименование юр. лица'),
            'create_date' => $this->string(50)->null()->comment('Дата регистрации'),
            'nds' => $this->integer()->null()->comment('НДС'),
            'tel' => $this->string(20)->null()->comment('Телефон'),
            'email' => $this->string(50)->null()->comment('Email'),
            'address_equally' => $this->integer()->null()->comment('Адрес совпадает с юридическим'),
            'signatory_fio' => $this->string(150)->null()->comment('ФИО подписанта'),
            'signatory_fio_genitive' => $this->string(150)->null()->comment('ФИО подписанта в родительном падеже'),
            'signatory_post' => $this->string(300)->null()->comment('Должность подписанта'),
            'signatory_type_id' => $this->integer()->null()->comment('Способ подписи'),
            'inn' => $this->string(20)->null()->comment('ИНН'),
            'snils' => $this->string(20)->null()->comment('СНИЛС'),
            'passport' => $this->string(12)->null()->comment('Серия, номер паспорта'),
            'passport_org' => $this->string(300)->null()->comment('Кем выдан'),
            'passport_date' => $this->string(20)->null()->comment('Дата выдачи'),
            'passport_org_code' => $this->string(8)->null()->comment('Код подразделения'),
            'date_add' => $this->integer()->null()->comment('Добавлен'),
            'kpp' => $this->string(20)->null()->comment('КПП'),
            'ogrn' => $this->string(20)->null()->comment('ОГРН'),
            'okpo' => $this->string(20)->null()->comment('ОКПО'),
            'oktmo' => $this->string(20)->null()->comment('ОКТМО'),
            'bik' => $this->string(20)->null()->comment('БИК'),
            'rs' => $this->string(20)->null()->comment('Р/С'),
            'ks' => $this->string(20)->null()->comment('К/С'),
            'bank_name' => $this->string(250)->null()->comment('Банк'),
            'date_birth' => $this->string(50)->null()->comment('Дата рождения'),
            'passport_birth_place' => $this->string(200)->null()->comment('Место рождения'),
            'created_at' => $this->timestamp()->null()->comment('Создано'),
            'updated_at' => $this->timestamp()->null()->comment('Обновлено'),
            'counteragent_box_id' => $this->string(255)->null()->comment('Идентификатор ящика контрагента в Диадоке'),
            'org_id' => $this->string(255)->null()->comment('Идентификатор организации в Диадоке'),
        ], $tableOptions);

        $this->createIndex('idx-requisites-requisites_type_id', '{{%requisites}}', 'requisites_type_id');
        $this->addForeignKey(
            'fk-requisites-requisites_type_id',
            '{{%requisites}}',
            'requisites_type_id',
            '{{%requisites_type}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-requisites-requisites_type_id', '{{%requisites}}');
        $this->dropTable('{{%requisites}}');
        $this->dropTable('{{%requisites_type}}');
    }
}
