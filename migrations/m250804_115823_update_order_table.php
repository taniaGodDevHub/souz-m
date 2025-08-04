<?php

use yii\db\Migration;

class m250804_115823_update_order_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'dealer_id', $this->integer());
    }
    public function safeDown()
    {
        $this->dropColumn('{{%order}}', 'dealer_id');
    }
}
