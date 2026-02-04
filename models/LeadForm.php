<?php

namespace app\models;

use app\modules\cars\models\CarMark;
use app\modules\cars\models\CarModel;
use app\modules\insurance_companies\models\InsuranceCompany;
use Yii;
use yii\base\Model;

/**
 * Модель формы создания лида.
 */
class LeadForm extends Model
{
    /** @var string Дата ДТП (Y-m-d) */
    public $dtp_date;
    /** @var string Время ДТП (H:i или H:i:s) */
    public $dtp_time;
    /** @var int|null */
    public $city_id;
    /** @var int|null */
    public $insurance_company_id;
    /** @var int|null */
    public $car_mark_id;
    /** @var int|null */
    public $car_model_id;
    /** @var string|null */
    public $car_number;
    /** @var int|null */
    public $client_id;
    /** @var string|null */
    public $report;
    /** @var int|null */
    public $status_id;

    public function rules()
    {
        return [
            [['city_id', 'insurance_company_id', 'client_id', 'status_id', 'car_mark_id', 'car_model_id'], 'integer'],
            [['dtp_date', 'dtp_time'], 'string'],
            [['report'], 'string'],
            [['car_number'], 'string', 'max' => 50],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['insurance_company_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceCompany::class, 'targetAttribute' => ['insurance_company_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => LeadStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['car_mark_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarMark::class, 'targetAttribute' => ['car_mark_id' => 'id']],
            [['car_model_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarModel::class, 'targetAttribute' => ['car_model_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'dtp_date' => 'Дата ДТП',
            'dtp_time' => 'Время ДТП',
            'city_id' => 'Город',
            'insurance_company_id' => 'Страховая компания',
            'car_mark_id' => 'Марка авто',
            'car_model_id' => 'Модель авто',
            'car_number' => 'Номер авто',
            'client_id' => 'Клиент',
            'report' => 'Отчёт партнёра',
            'status_id' => 'Статус',
        ];
    }

    /**
     * Создаёт лид и при наличии status_id — запись в lead_status_history.
     * @return Lead|null
     */
    public function createLead(): ?Lead
    {
        if (!$this->validate()) {
            return null;
        }

        $lead = new Lead();
        $lead->dtp_date = $this->getDtpTimestamp();
        $lead->city_id = $this->city_id ?: null;
        $lead->insurance_company_id = $this->insurance_company_id ?: null;
        $lead->car_mark_id = $this->car_mark_id ?: null;
        $lead->car_model_id = $this->car_model_id ?: null;
        $lead->car_number = $this->car_number ?: null;
        $lead->client_id = $this->client_id ?: null;
        $lead->report = $this->report ?: null;
        $lead->status_id = $this->status_id ?: null;
        $lead->partner_id = !Yii::$app->user->isGuest ? (int) Yii::$app->user->id : null;

        if (!$lead->save(false)) {
            return null;
        }

        if ($lead->status_id) {
            $history = new LeadStatusHistory();
            $history->lead_id = $lead->id;
            $history->status_id = $lead->status_id;
            $history->date_add = time();
            $history->save(false);
        }

        return $lead;
    }

    /**
     * Собирает из dtp_date и dtp_time один Unix timestamp для записи в dtp_date.
     */
    public function getDtpTimestamp(): ?int
    {
        $date = trim((string) $this->dtp_date);
        $time = trim((string) $this->dtp_time);
        if ($date === '' && $time === '') {
            return null;
        }
        if ($time !== '') {
            $date = $date !== '' ? $date . ' ' . $time : date('Y-m-d') . ' ' . $time;
        }
        $ts = strtotime($date);
        return $ts !== false ? $ts : null;
    }
}
