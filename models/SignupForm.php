<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $fio;
    public $username;
    public $tel;
    public $password;
    public $agree_rules;
    public $role = 'user';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fio', 'username', 'tel', 'password', 'agree_rules'], 'required'],
            ['username', 'trim'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Это имя в системе уже занято.'],
            ['tel', 'trim'],
            ['tel', 'string', 'max' => 20],
            ['tel', function ($attribute, $params, $validator) {
                $tel = preg_replace('/\D+/', '', $this->tel);
                if (strlen($tel) === 11 && $tel[0] === '8') {
                    $tel = '7' . substr($tel, 1);
                }
                if (strlen($tel) !== 11 || $tel[0] !== '7') {
                    $this->addError($attribute, 'Введите номер в формате +7 (999) 999-99-99');
                    return;
                }
                $existing = User::findOne(['tel' => $tel]);
                if ($existing) {
                    $this->addError($attribute, 'Этот номер телефона уже зарегистрирован.');
                }
            }],
            ['password', 'string', 'min' => 6],
            ['agree_rules', 'boolean'],
            ['agree_rules', 'compare', 'compareValue' => true, 'message' => 'Необходимо согласие с Правилами и Условиями.'],
            ['role', 'string', 'max' => 30],
            ['role', function ($attribute, $params, $validator) {
                if (!in_array($this->$attribute, ['user', 'manufacturer', 'provider'])) {
                    $this->addError($attribute, 'Неверная роль.');
                }
            }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * Нормализует телефон до 7XXXXXXXXXX
     */
    public function getNormalizedTel(): string
    {
        $tel = preg_replace('/\D+/', '', $this->tel);
        if (strlen($tel) === 11 && $tel[0] === '8') {
            $tel = '7' . substr($tel, 1);
        }
        return $tel;
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
        $user->tel = $this->getNormalizedTel();
        $user->email = $this->username . '@partner.local';
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if (!$user->save()) {
            Yii::$app->session->setFlash('danger', 'Регистрация не удалась.');
            return null;
        }

        $parts = array_map('trim', preg_split('/\s+/u', $this->fio, 3, PREG_SPLIT_NO_EMPTY));
        $profile = new UserProfile();
        $profile->user_id = $user->id;
        $profile->f = $parts[0] ?? null;
        $profile->i = $parts[1] ?? null;
        $profile->o = $parts[2] ?? null;
        $profile->dealer_id = null;
        if (!$profile->save()) {
            Yii::$app->session->setFlash('warning', 'Профиль создан, но не все поля сохранены. Проверьте настройки профиля.');
        }
        return $user;
    }

    public function attributeLabels()
    {
        return [
            'fio' => 'ФИО',
            'username' => 'Имя в системе',
            'tel' => 'Номер телефона',
            'password' => 'Пароль',
            'agree_rules' => 'Согласие с правилами',
        ];
    }
}