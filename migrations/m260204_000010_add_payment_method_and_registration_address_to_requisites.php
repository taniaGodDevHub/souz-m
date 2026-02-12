<?php

use yii\db\Migration;

/**
 * Добавляет в requisites поле способа оплаты и адрес регистрации.
 */
class m260204_000010_add_payment_method_and_registration_address_to_requisites extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%requisites}}', 'payment_method', $this->string(20)->null()->comment('Способ оплаты: sbp, bank'));
        $this->addColumn('{{%requisites}}', 'registration_address', $this->string(500)->null()->comment('Адрес регистрации'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%requisites}}', 'payment_method');
        $this->dropColumn('{{%requisites}}', 'registration_address');
    }
}
