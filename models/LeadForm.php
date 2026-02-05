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
    /** @var string|null Фамилия (user_profile.f) */
    public $f;
    /** @var string|null Имя (user_profile.i) */
    public $i;
    /** @var string|null Отчество (user_profile.o) */
    public $o;
    /** @var string|null Телефон (user.tel) */
    public $tel;
    /** @var string|null */
    public $report;
    /** @var int|null */
    public $status_id;

    public function rules()
    {
        return [
            [['city_id', 'insurance_company_id', 'client_id', 'status_id', 'car_mark_id', 'car_model_id'], 'integer'],
            [['dtp_date', 'dtp_time', 'f', 'i', 'o', 'tel'], 'string'],
            [['report'], 'string'],
            [['car_number'], 'string', 'max' => 50],
            [['f', 'i', 'o'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 20],
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
            'f' => 'Фамилия',
            'i' => 'Имя',
            'o' => 'Отчество',
            'tel' => 'Телефон',
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

        if (empty($this->client_id)) {
            $user = $this->createClientUser();
            if (!$user) {
                return null;
            }
            $this->client_id = (int) $user->id;
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

    /**
     * Нормализует телефон до 7XXXXXXXXXX.
     */
    public static function normalizeTel(string $tel): string
    {
        $tel = preg_replace('/\D+/', '', $tel);
        if (strlen($tel) === 11 && $tel[0] === '8') {
            $tel = '7' . substr($tel, 1);
        }
        return $tel;
    }

    /**
     * Создаёт пользователя и профиль с ролью client. Требует заполненный tel.
     * @return User|null
     */
    protected function createClientUser(): ?User
    {
        $telNorm = self::normalizeTel(trim((string) $this->tel));
        if (strlen($telNorm) !== 11 || $telNorm[0] !== '7') {
            $this->addError('tel', 'Введите корректный телефон для создания клиента.');
            return null;
        }
        $existing = User::findOne(['tel' => $telNorm, 'status' => User::STATUS_ACTIVE]);
        if ($existing) {
            $this->addError('tel', 'Пользователь с таким телефоном уже существует. Выберите его из подсказок.');
            return null;
        }

        $user = new User();
        $user->username = 'client_' . $telNorm;
        $user->tel = $telNorm;
        $user->email = $user->username . '@client.local';
        $user->setPassword(Yii::$app->security->generateRandomString(12));
        $user->generateAuthKey();

        if (!$user->save(false)) {
            $this->addError('tel', 'Не удалось создать пользователя.');
            return null;
        }

        $profile = new UserProfile();
        $profile->user_id = $user->id;
        $profile->f = trim((string) $this->f) ?: null;
        $profile->i = trim((string) $this->i) ?: null;
        $profile->o = trim((string) $this->o) ?: null;
        $profile->save(false);

        $auth = Yii::$app->authManager;
        $clientRole = $auth->getRole('client');
        if ($clientRole) {
            $auth->assign($clientRole, $user->id);
        }

        return $user;
    }
}
