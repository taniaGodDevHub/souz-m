<?php

use yii\db\Migration;

/**
 * Добавляет в таблицу lead поля car_mark_id и car_model_id (связи с car_mark, car_model).
 */
class m260204_000006_add_car_mark_and_model_to_lead extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%lead}}', 'car_mark_id', $this->integer()->null()->comment('Марка авто'));
        $this->addColumn('{{%lead}}', 'car_model_id', $this->integer()->null()->comment('Модель авто'));

        $this->addForeignKey(
            'fk-lead-car_mark_id',
            '{{%lead}}',
            'car_mark_id',
            '{{%car_mark}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-lead-car_model_id',
            '{{%lead}}',
            'car_model_id',
            '{{%car_model}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-lead-car_model_id', '{{%lead}}');
        $this->dropForeignKey('fk-lead-car_mark_id', '{{%lead}}');
        $this->dropColumn('{{%lead}}', 'car_model_id');
        $this->dropColumn('{{%lead}}', 'car_mark_id');
    }
}
