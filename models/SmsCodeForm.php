<?php

namespace app\models;

use yii\base\Model;

class SmsCodeForm extends Model
{
    public string $code = '';

    public function rules(): array
    {
        return [
            [['code'], 'required'],
            [['code'], 'match', 'pattern' => '/^\d{6}$/', 'message' => 'Введите 6 цифр'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'code' => 'Код из СМС',
        ];
    }
}

