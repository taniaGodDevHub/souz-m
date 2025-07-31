<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string|null $client_name
 * @property string|null $client_phone
 * @property string|null $type_order
 * @property int|null $city_id
 */
class Order extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_name', 'client_phone', 'type_order', 'city_id'], 'default', 'value' => null],
            [['city_id'], 'integer'],
            [['client_name', 'client_phone', 'type_order'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_name' => 'Клиент',
            'client_phone' => 'Телефон',
            'type_order' => 'Тип заказа',
            'city_id' => 'ID города',
        ];
    }

}
