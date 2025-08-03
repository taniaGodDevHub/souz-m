<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $username;
    public $email;
    public $password;
    public $role;
    public $tg_login;
    public $dealer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
            ['tg_login', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот логин уже используется'],
            ['dealer_id', 'required'],
            ['dealer_id', 'integer'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['role', 'required'],
            ['role', 'string', 'max' => 30],
            ['role', function ($attribute, $params, $validator) {
                if (!in_array($this->$attribute, ['user', 'manufacturer', 'provider'])) {
                    $this->addError($attribute, 'Не хорошо пытаться взламывать чужие сайты!');
                }
            },  'skipOnEmpty' => false],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->tg_login = $this->tg_login;

        if (!$user->save()) {
            Yii::$app->session->setFlash('Регистрация не удалась');
            return null;
        }

        $profile = new UserProfile();
        $profile->user_id = $user->id;
        $profile->dealer_id = $this->dealer_id;
        if (!$profile->save()) {
            Yii::$app->session->setFlash('Не удалось создать профиль. Создайте его в настройках профиля');
        }
        return $user;
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Email',
            'tg_login' => 'Логин в TG без @',
            'dealer_id' => 'Дилер',
        ];
    }

}