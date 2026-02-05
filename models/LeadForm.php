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
    /** @var array Массивы загруженных файлов: [ ['path' => '...', 'name' => '...'], ... ] */
    public $photos = [];
    /** @var array То же для PDF */
    public $pdf = [];

    public function rules()
    {
        return [
            [['city_id', 'insurance_company_id', 'client_id', 'status_id', 'car_mark_id', 'car_model_id'], 'filter', 'filter' => function ($v) {
                return $v === '' || $v === null ? null : (int) $v;
            }],
            [['city_id', 'insurance_company_id', 'client_id', 'status_id', 'car_mark_id', 'car_model_id'], 'integer'],
            [['dtp_date', 'dtp_time', 'f', 'i', 'o', 'tel'], 'string'],
            [['report'], 'string'],
            [['car_number'], 'string', 'max' => 50],
            [['f', 'i', 'o'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 20],
            [['city_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['insurance_company_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => InsuranceCompany::class, 'targetAttribute' => ['insurance_company_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => LeadStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['car_mark_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => CarMark::class, 'targetAttribute' => ['car_mark_id' => 'id']],
            [['car_model_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => CarModel::class, 'targetAttribute' => ['car_model_id' => 'id']],
            [['photos', 'pdf'], 'safe'],
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
            Yii::warning(['LeadForm validation failed' => $this->getErrors()], __METHOD__);
            return null;
        }

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            if (empty($this->client_id)) {
                $user = $this->createClientUser();
                if (!$user) {
                    $transaction->rollBack();
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
                Yii::warning(['Lead save failed' => $lead->getErrors()], __METHOD__);
                $transaction->rollBack();
                return null;
            }

            if ($lead->status_id) {
                $history = new LeadStatusHistory();
                $history->lead_id = $lead->id;
                $history->status_id = $lead->status_id;
                $history->date_add = time();
                $history->save(false);
            }

            $this->saveLeadFiles($lead->id, array_merge(
                $this->normalizeFileItems($this->photos),
                $this->normalizeFileItems($this->pdf)
            ));

            $transaction->commit();
            return $lead;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Нормализует массив файлов из POST (photos[i][path], photos[i][name]) в список [['path'=>..., 'name'=>...], ...].
     * @param array $items
     * @return array
     */
    protected function normalizeFileItems(array $items): array
    {
        $out = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $path = isset($item['path']) ? trim((string) $item['path']) : '';
            $name = isset($item['name']) ? trim((string) $item['name']) : '';
            if ($path !== '') {
                $out[] = ['path' => $path, 'name' => $name ?: basename($path)];
            }
        }
        return $out;
    }

    /**
     * Сохраняет записи в lead_files для лида.
     * @param int $leadId
     * @param array $files [ ['path' => '...', 'name' => '...'], ... ]
     */
    protected function saveLeadFiles(int $leadId, array $files): void
    {
        foreach ($files as $file) {
            $path = $file['path'] ?? '';
            $name = $file['name'] ?? basename($path);
            if ($path === '') {
                continue;
            }
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if (strlen($ext) > 5) {
                $ext = substr($ext, 0, 5);
            }
            $lf = new LeadFile();
            $lf->lead_id = $leadId;
            $lf->name = $name;
            $lf->extention = $ext !== '' ? $ext : null;
            $lf->file_path = $path;
            $lf->save(false);
        }
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
