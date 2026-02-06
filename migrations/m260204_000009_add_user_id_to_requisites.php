<?php

use yii\db\Migration;

/**
 * Добавляет в таблицу requisites поле user_id (связь с user).
 */
class m260204_000009_add_user_id_to_requisites extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%requisites}}', 'user_id', $this->integer()->null()->comment('Пользователь'));
        $this->addForeignKey(
            'fk-requisites-user_id',
            '{{%requisites}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-requisites-user_id', '{{%requisites}}');
        $this->dropColumn('{{%requisites}}', 'user_id');
    }
}
