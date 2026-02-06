<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $dealer_id
 * @property string|null $f
 * @property string|null $i
 * @property string|null $o
 * @property string|null $tel
 */
class UserProfile extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','dealer_id', 'f', 'i', 'o', 'tel'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['f', 'i', 'o', 'tel'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID пользователя',
            'f' => 'Фамилия',
            'i' => 'Имя',
            'o' => 'Отчество',
            'tel' => 'Телефон',
            'dealer_id' => 'Дилер',
        ];
    }

    public function getDealer()
    {
        return $this->hasOne(Dealers::className(),['id'=>'dealer_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Виртуальное поле: логин Telegram из связанной модели User.
     * @return string
     */
    public function getTgLogin()
    {
        return $this->user ? (string) $this->user->tg_login : '';
    }

}
