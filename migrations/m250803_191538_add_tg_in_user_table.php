<?php

use yii\db\Migration;

class m250803_191538_add_tg_in_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'tg_login', $this->string()->unique());
        $this->addColumn('{{%user}}', 'tg_id', $this->bigInteger()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'tg_login');
        $this->dropColumn('{{%user}}', 'tg_id');
    }
}
