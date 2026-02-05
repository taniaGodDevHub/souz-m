<?php

use yii\db\Migration;

/**
 * Добавляет в таблицу lead колонку profit decimal(11,2).
 */
class m260204_000007_add_profit_to_lead extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%lead}}', 'profit', $this->decimal(11, 2)->null()->comment('Профит'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%lead}}', 'profit');
    }
}
