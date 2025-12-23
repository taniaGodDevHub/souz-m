<?php

namespace app\modules\billing\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $project_id
 * @property string $name
 *
 * @property User $user
 * @property Transaction[] $outgoingTransactions
 * @property Transaction[] $incomingTransactions
 */
class Account extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['user_id', 'project_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['user_id', 'project_id'], 'validateAccountType'],
        ];
    }

    /**
     * Валидация типа счета
     * Либо user_id, либо project_id должен быть заполнен (или оба NULL для системных)
     */
    public function validateAccountType()
    {
        // Системный счет (оба NULL) - OK
        if ($this->user_id === null && $this->project_id === null) {
            return;
        }
        
        // Нельзя иметь одновременно user_id и project_id
        if ($this->user_id !== null && $this->project_id !== null) {
            $this->addError('user_id', 'Счет не может быть одновременно счетом пользователя и проекта');
            $this->addError('project_id', 'Счет не может быть одновременно счетом пользователя и проекта');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID пользователя',
            'project_id' => 'ID проекта',
            'name' => 'Название счета',
        ];
    }

    /**
     * Связь с пользователем
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Исходящие транзакции (где этот счет - отправитель)
     * @return \yii\db\ActiveQuery
     */
    public function getOutgoingTransactions()
    {
        return $this->hasMany(Transaction::className(), ['from_acc_id' => 'id']);
    }

    /**
     * Входящие транзакции (где этот счет - получатель)
     * @return \yii\db\ActiveQuery
     */
    public function getIncomingTransactions()
    {
        return $this->hasMany(Transaction::className(), ['to_acc_id' => 'id']);
    }

    /**
     * Получить баланс счета в копейках
     * @return int
     */
    public function getBalance()
    {
        $incoming = (int)$this->getIncomingTransactions()->sum('amount') ?: 0;
        $outgoing = (int)$this->getOutgoingTransactions()->sum('amount') ?: 0;
        return $incoming - $outgoing;
    }

    /**
     * Получить баланс счета в рублях
     * @return float
     */
    public function getBalanceInRubles()
    {
        return $this->getBalance() / 100;
    }

    /**
     * Проверка, является ли счет системным
     * @return bool
     */
    public function isSystem()
    {
        return $this->user_id === null && $this->project_id === null;
    }

    /**
     * Проверка, является ли счет счетом проекта
     * @return bool
     */
    public function isProject()
    {
        return $this->project_id !== null;
    }

    /**
     * Проверка, является ли счет счетом пользователя
     * @return bool
     */
    public function isUser()
    {
        return $this->user_id !== null && $this->project_id === null;
    }
}

