<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель таблицы requisites.
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $requisites_type_id
 * @property string|null $ur_name
 * @property string|null $ur_full_name
 * @property string|null $create_date
 * @property int|null $nds
 * @property string|null $tel
 * @property string|null $email
 * @property int|null $address_equally
 * @property string|null $signatory_fio
 * @property string|null $signatory_fio_genitive
 * @property string|null $signatory_post
 * @property int|null $signatory_type_id
 * @property string|null $inn
 * @property string|null $snils
 * @property string|null $passport
 * @property string|null $passport_org
 * @property string|null $passport_date
 * @property string|null $passport_org_code
 * @property int|null $date_add
 * @property string|null $kpp
 * @property string|null $ogrn
 * @property string|null $okpo
 * @property string|null $oktmo
 * @property string|null $bik
 * @property string|null $rs
 * @property string|null $ks
 * @property string|null $bank_name
 * @property string|null $date_birth
 * @property string|null $passport_birth_place
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $counteragent_box_id
 * @property string|null $org_id
 *
 * @property RequisitesType $requisitesType
 * @property User $user
 */
class Requisites extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%requisites}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'requisites_type_id', 'nds', 'address_equally', 'signatory_type_id', 'date_add'], 'integer'],
            [[
                'ur_name', 'ur_full_name', 'create_date', 'tel', 'email', 'signatory_fio', 'signatory_fio_genitive',
                'signatory_post', 'inn', 'snils', 'passport', 'passport_org', 'passport_date', 'passport_org_code',
                'kpp', 'ogrn', 'okpo', 'oktmo', 'bik', 'rs', 'ks', 'bank_name', 'date_birth', 'passport_birth_place',
                'counteragent_box_id', 'org_id',
            ], 'string'],
            [['ur_name'], 'string', 'max' => 500],
            [['ur_full_name'], 'string', 'max' => 1500],
            [['signatory_fio', 'signatory_fio_genitive'], 'string', 'max' => 150],
            [['passport'], 'string', 'max' => 12],
            [['passport_org_code'], 'string', 'max' => 8],
            [['requisites_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequisitesType::class, 'targetAttribute' => ['requisites_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'requisites_type_id' => 'Правовая форма',
            'ur_name' => 'Краткое наименование юр. лица',
            'ur_full_name' => 'Полное наименование юр. лица',
            'create_date' => 'Дата регистрации',
            'nds' => 'НДС',
            'tel' => 'Телефон',
            'email' => 'Email',
            'address_equally' => 'Адрес совпадает с юридическим',
            'signatory_fio' => 'ФИО подписанта',
            'signatory_fio_genitive' => 'ФИО подписанта в родительном падеже',
            'signatory_post' => 'Должность подписанта',
            'signatory_type_id' => 'Способ подписи',
            'inn' => 'ИНН',
            'snils' => 'СНИЛС',
            'passport' => 'Серия, номер паспорта',
            'passport_org' => 'Кем выдан',
            'passport_date' => 'Дата выдачи',
            'passport_org_code' => 'Код подразделения',
            'date_add' => 'Добавлен',
            'kpp' => 'КПП',
            'ogrn' => 'ОГРН',
            'okpo' => 'ОКПО',
            'oktmo' => 'ОКТМО',
            'bik' => 'БИК',
            'rs' => 'Р/С',
            'ks' => 'К/С',
            'bank_name' => 'Банк',
            'date_birth' => 'Дата рождения',
            'passport_birth_place' => 'Место рождения',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'counteragent_box_id' => 'Идентификатор ящика контрагента в Диадоке',
            'org_id' => 'Идентификатор организации в Диадоке',
        ];
    }

    public function getRequisitesType()
    {
        return $this->hasOne(RequisitesType::class, ['id' => 'requisites_type_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
