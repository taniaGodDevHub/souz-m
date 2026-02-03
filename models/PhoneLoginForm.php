<?php

namespace app\models;

use yii\base\Model;

class PhoneLoginForm extends Model
{
    public string $tel = '';

    public function rules(): array
    {
        return [
            [['tel'], 'required'],
            [['tel'], 'string'],
            [['tel'], 'validateTel'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'tel' => 'Телефон',
        ];
    }

    public function getNormalizedTel(): string
    {
        $digits = preg_replace('/\D+/', '', $this->tel);
        if (strlen($digits) === 11 && $digits[0] === '8') {
            $digits = '7' . substr($digits, 1);
        }
        return $digits;
    }

    public function validateTel(): void
    {
        $digits = $this->getNormalizedTel();
        if (!preg_match('/^7\d{10}$/', $digits)) {
            $this->addError('tel', 'Введите телефон в формате +7 (XXX) XXX-XX-XX');
        }
    }
}

