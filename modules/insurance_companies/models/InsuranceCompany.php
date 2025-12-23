<?php

namespace app\modules\insurance_companies\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "insurance_company".
 *
 * @property int $id
 * @property string $full_name Полное наименование
 * @property string|null $short_name Краткое наименование
 * @property string|null $previous_name Прежнее наименование
 * @property string|null $license_number Номер Лицензии Минфина
 * @property string|null $license_date Дата Лицензии Минфина
 * @property string|null $rsa_certificate_number Номер Свидетельства РСА
 * @property string|null $rsa_certificate_date Дата Свидетельства РСА
 * @property string|null $phone_fax Основной телефон/факс
 * @property string|null $email E-mail общий
 * @property string|null $website Сайт
 * @property string|null $address Адрес
 * @property string|null $inn ИНН
 * @property int|null $created_at Дата создания
 * @property int|null $updated_at Дата обновления
 */
class InsuranceCompany extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%insurance_company}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name'], 'required'],
            [['license_date', 'rsa_certificate_date'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
            [['full_name', 'short_name', 'previous_name', 'phone_fax', 'website'], 'string', 'max' => 255],
            [['license_number', 'rsa_certificate_number', 'inn'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['address'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Полное наименование',
            'short_name' => 'Краткое наименование',
            'previous_name' => 'Прежнее наименование',
            'license_number' => 'Номер Лицензии Минфина',
            'license_date' => 'Дата Лицензии Минфина',
            'rsa_certificate_number' => 'Номер Свидетельства РСА',
            'rsa_certificate_date' => 'Дата Свидетельства РСА',
            'phone_fax' => 'Основной телефон/факс',
            'email' => 'E-mail общий',
            'website' => 'Сайт',
            'address' => 'Адрес',
            'inn' => 'ИНН',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Форматирование даты для отображения
     * @param string|null $date
     * @return string
     */
    public function formatDate($date)
    {
        if (empty($date)) {
            return '—';
        }
        return date('d.m.Y', strtotime($date));
    }
}

