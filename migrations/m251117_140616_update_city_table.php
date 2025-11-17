<?php

use yii\db\Migration;

class m251117_140616_update_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%city}}', 'accept', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251117_140616_update_city_table cannot be reverted.\n";

        return false;
    }
}
