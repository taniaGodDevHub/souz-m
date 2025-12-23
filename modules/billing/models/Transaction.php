<?php

namespace app\modules\billing\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int|null $from_acc_id
 * @property int|null $to_acc_id
 * @property int $transaction_type
 * @property int $amount Сумма в копейках
 * @property int $date_add
 *
 * @property Account $fromAccount
 * @property Account $toAccount
 * @property TransactionType $type
 */
class Transaction extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    /**
     * Виртуальное поле для формы (рубли)
     * @var float
     */
    public $amount_rubles;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_type', 'amount', 'date_add'], 'required'],
            [['from_acc_id', 'to_acc_id', 'transaction_type', 'amount', 'date_add'], 'integer'],
            [['amount'], 'integer', 'min' => 1],
            [['amount_rubles'], 'number', 'min' => 0.01],
            [['from_acc_id', 'to_acc_id'], 'validateAtLeastOneAccount'],
            [['from_acc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['from_acc_id' => 'id']],
            [['to_acc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['to_acc_id' => 'id']],
            [['transaction_type'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionType::className(), 'targetAttribute' => ['transaction_type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_acc_id' => 'Счет отправителя',
            'to_acc_id' => 'Счет получателя',
            'transaction_type' => 'Тип транзакции',
            'amount' => 'Сумма (копейки)',
            'date_add' => 'Дата создания',
        ];
    }

    /**
     * Валидация: хотя бы один счет должен быть указан
     */
    public function validateAtLeastOneAccount()
    {
        if ($this->from_acc_id === null && $this->to_acc_id === null) {
            $this->addError('from_acc_id', 'Необходимо указать хотя бы один счет (отправителя или получателя)');
            $this->addError('to_acc_id', 'Необходимо указать хотя бы один счет (отправителя или получателя)');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert && $this->date_add === null) {
                $this->date_add = time();
            }
            // Преобразуем рубли в копейки, если указано поле amount_rubles
            if ($this->amount_rubles !== null && $this->amount_rubles !== '') {
                $this->setAmountFromRubles((float)$this->amount_rubles);
            }
            return true;
        }
        return false;
    }

    /**
     * Связь со счетом отправителя
     * @return \yii\db\ActiveQuery
     */
    public function getFromAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'from_acc_id']);
    }

    /**
     * Связь со счетом получателя
     * @return \yii\db\ActiveQuery
     */
    public function getToAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'to_acc_id']);
    }

    /**
     * Связь с типом транзакции
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TransactionType::className(), ['id' => 'transaction_type']);
    }

    /**
     * Получить сумму в рублях
     * @return float
     */
    public function getAmountInRubles()
    {
        return $this->amount / 100;
    }

    /**
     * Установить сумму из рублей
     * @param float $rubles
     */
    public function setAmountFromRubles($rubles)
    {
        $this->amount = (int)round($rubles * 100);
    }
}

