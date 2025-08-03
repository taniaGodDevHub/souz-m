<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "dealers".
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 */
class Dealers extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dealers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'phone', 'email'], 'default', 'value' => null],
            [['city_id', 'name'], 'required'],
            [['city_id'], 'integer'],
            [['name', 'address', 'phone', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
        ];
    }

    public static function getList(){
        return ArrayHelper::map(Dealers::find()->all(),'id','name');
    }
}
