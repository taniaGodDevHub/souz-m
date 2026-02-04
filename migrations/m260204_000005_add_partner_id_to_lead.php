<?php

use yii\db\Migration;

/**
 * Добавляет в таблицу lead поле partner_id (связь с user).
 */
class m260204_000005_add_partner_id_to_lead extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%lead}}', 'partner_id', $this->integer()->null()->comment('Партнёр'));
        $this->addForeignKey(
            'fk-lead-partner_id',
            '{{%lead}}',
            'partner_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-lead-partner_id', '{{%lead}}');
        $this->dropColumn('{{%lead}}', 'partner_id');
    }
}
