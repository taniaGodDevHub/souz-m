<?php

use yii\db\Migration;

class m250823_103830_update_souz_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('souz_product', 'gallery', $this->text(3000));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250823_103830_update_souz_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250823_103830_update_souz_product_table cannot be reverted.\n";

        return false;
    }
    */
}
