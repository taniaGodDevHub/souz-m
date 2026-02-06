<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель формы профиля пользователя.
 * Объединяет данные из UserProfile и User (tg_login, tel при необходимости).
 */
class UserProfileForm extends Model
{
    /** @var int|null */
    public $user_id;
    /** @var string|null */
    public $f;
    /** @var string|null */
    public $i;
    /** @var string|null */
    public $o;
    /** @var string|null */
    public $tel;
    /** @var string|null Логин Telegram (из User) */
    public $tg_login;
    /** @var string|null Пароль не редактируется через форму, только отображение */
    public $password;

    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['f', 'i', 'o', 'tel', 'tg_login', 'password'], 'string', 'max' => 255],
            [['user_id', 'f', 'i', 'o', 'tel', 'tg_login', 'password'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'ID пользователя',
            'f' => 'Фамилия',
            'i' => 'Имя',
            'o' => 'Отчество',
            'tel' => 'Телефон',
            'tg_login' => 'Telegram',
            'password' => 'Пароль',
        ];
    }

    /**
     * Заполняет форму из UserProfile и связанного User.
     * @param UserProfile $profile
     * @return void
     */
    public function loadFromProfile(UserProfile $profile)
    {
        $this->user_id = $profile->user_id;
        $this->f = $profile->f;
        $this->i = $profile->i;
        $this->o = $profile->o;
        $this->tel = $profile->tel !== null && $profile->tel !== '' ? $profile->tel : ($profile->user && $profile->user->tel ? $profile->user->tel : '');
        if ($profile->user) {
            $this->tg_login = $profile->user->tg_login;
            $this->password = ''; // не показываем реальный пароль
        } else {
            $this->tg_login = '';
            $this->password = '';
        }
    }

    /**
     * Сохраняет данные формы в UserProfile и при необходимости в User (tg_login).
     * @param UserProfile $profile
     * @return bool
     */
    public function saveToProfile(UserProfile $profile)
    {
        if (!$this->validate()) {
            return false;
        }
        $profile->f = $this->f;
        $profile->i = $this->i;
        $profile->o = $this->o;
        $profile->tel = $this->tel;
        if (!$profile->save(false)) {
            return false;
        }
        if ($profile->user && $this->tg_login !== null && $this->tg_login !== '') {
            $profile->user->tg_login = $this->tg_login;
            $profile->user->save(false);
        }
        return true;
    }
}
