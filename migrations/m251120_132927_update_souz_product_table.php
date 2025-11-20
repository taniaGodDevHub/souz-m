<?php

use yii\db\Migration;

class m251120_132927_update_souz_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%souz_product}}', 'thumbnail_url_medium', $this->string(1500)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251120_132927_update_souz_product_table cannot be reverted.\n";

        return false;
    }
}
