<?php

use yii\db\Migration;

/**
 * Миграция для добавления дополнительных полей в таблицу страховых компаний
 */
class m251222_190000_add_additional_fields_to_insurance_company extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем новые поля, если их еще нет
        $table = $this->db->schema->getTableSchema('{{%insurance_company}}');
        
        if (!isset($table->columns['website'])) {
            $this->addColumn('{{%insurance_company}}', 'website', $this->string(255)->comment('Сайт'));
        }
        
        if (!isset($table->columns['address'])) {
            $this->addColumn('{{%insurance_company}}', 'address', $this->text()->comment('Адрес'));
        }
        
        if (!isset($table->columns['inn'])) {
            $this->addColumn('{{%insurance_company}}', 'inn', $this->string(20)->comment('ИНН'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%insurance_company}}', 'website');
        $this->dropColumn('{{%insurance_company}}', 'address');
        $this->dropColumn('{{%insurance_company}}', 'inn');
    }
}

