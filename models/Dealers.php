<?php

namespace app\models;

use Yii;
use yii\debug\models\search\Profile;
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
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'Город',
            'name' => 'Название',
            'address' => 'Адрес',
            'phone' => 'Телефон',
            'email' => 'Email',
        ];
    }

    public static function getList(){
        return ArrayHelper::map(Dealers::find()->all(),'id','name');
    }

    public function getProfileWithUser()
    {
        return $this->hasOne(UserProfile::className(), ['dealer_id' => 'id'])->with(['user']);
    }

    /**
     * Связь с городом
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
