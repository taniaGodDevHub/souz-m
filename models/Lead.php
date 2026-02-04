<?php

namespace app\models;

use app\modules\cars\models\CarMark;
use app\modules\cars\models\CarModel;
use app\modules\insurance_companies\models\InsuranceCompany;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель таблицы lead.
 *
 * @property int $id
 * @property int|null $dtp_date
 * @property int|null $city_id
 * @property int|null $insurance_company_id
 * @property string|null $car_number
 * @property int|null $client_id
 * @property string|null $report
 * @property int|null $date_add
 * @property int|null $status_id
 * @property int|null $partner_id
 * @property int|null $car_mark_id
 * @property int|null $car_model_id
 *
 * @property City $city
 * @property InsuranceCompany $insuranceCompany
 * @property User $client
 * @property User $partner
 * @property LeadStatus $status
 * @property LeadFile[] $leadFiles
 */
class Lead extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lead}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_add',
                'updatedAtAttribute' => false,
                'value' => time(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['dtp_date', 'city_id', 'insurance_company_id', 'client_id', 'date_add', 'status_id', 'partner_id', 'car_mark_id', 'car_model_id'], 'integer'],
            [['report'], 'string'],
            [['car_number'], 'string', 'max' => 50],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['insurance_company_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceCompany::class, 'targetAttribute' => ['insurance_company_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => LeadStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['partner_id' => 'id']],
            [['car_mark_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarMark::class, 'targetAttribute' => ['car_mark_id' => 'id']],
            [['car_model_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarModel::class, 'targetAttribute' => ['car_model_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dtp_date' => 'Дата ДТП',
            'city_id' => 'Город',
            'insurance_company_id' => 'Страховая компания',
            'car_number' => 'Номер авто',
            'client_id' => 'Клиент',
            'report' => 'Отчёт партнёра',
            'date_add' => 'Дата добавления',
            'status_id' => 'Статус',
            'partner_id' => 'Партнёр',
            'car_mark_id' => 'Марка авто',
            'car_model_id' => 'Модель авто',
        ];
    }

    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    public function getInsuranceCompany()
    {
        return $this->hasOne(InsuranceCompany::class, ['id' => 'insurance_company_id']);
    }

    public function getClient()
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
    }

    public function getPartner()
    {
        return $this->hasOne(User::class, ['id' => 'partner_id']);
    }

    public function getCarMark()
    {
        return $this->hasOne(CarMark::class, ['id' => 'car_mark_id']);
    }

    public function getCarModel()
    {
        return $this->hasOne(CarModel::class, ['id' => 'car_model_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(LeadStatus::class, ['id' => 'status_id']);
    }

    public function getLeadFiles()
    {
        return $this->hasMany(LeadFile::class, ['lead_id' => 'id']);
    }
}
