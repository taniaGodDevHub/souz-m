<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%souz_product}}`.
 */
class m250819_094902_create_souz_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%souz_product}}', [
            'id' => $this->primaryKey(),
            'ex_id' => $this->integer()->notNull(),
            'title' => $this->string()->null(),
            'description' => $this->text(),
            'sku' => $this->string()->null(),
            'excerpt' => $this->text(),
            'price' => $this->string(),
            'regular_price' => $this->string(),
            'sale_price' => $this->string(),
            'stock_status' => $this->string(),
            'categories' => $this->string(),
            'subcategory' => $this->string(),
            'thumbnail_url' => $this->string(),
            'gallery' => $this->string(),
            'permalink' => $this->string(),
            'attributes' => $this->string(),
            'date_update' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%souz_product}}');
    }
}
