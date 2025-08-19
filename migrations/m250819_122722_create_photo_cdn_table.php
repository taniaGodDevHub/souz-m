<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%photo_cdn}}`.
 */
class m250819_122722_create_photo_cdn_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%photo_cdn}}', [
            'id' => $this->primaryKey(),
            'ex_link' => $this->string(2500),
            'in_link' => $this->string(2500),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%photo_cdn}}');
    }
}
