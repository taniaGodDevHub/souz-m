<?php

use yii\db\Migration;

/**
 * Создаёт таблицу lead_files.
 */
class m260204_000003_create_lead_files_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%lead_files}}', [
            'id' => $this->primaryKey(),
            'lead_id' => $this->integer()->notNull()->comment('Лид'),
            'name' => $this->string(255)->notNull()->comment('Имя файла'),
            'extention' => $this->string(5)->null()->comment('Расширение файла'),
            'file_path' => $this->string(1500)->notNull()->comment('Путь до файла'),
        ]);

        $this->addForeignKey(
            'fk-lead_files-lead_id',
            '{{%lead_files}}',
            'lead_id',
            '{{%lead}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-lead_files-lead_id', '{{%lead_files}}');
        $this->dropTable('{{%lead_files}}');
    }
}
