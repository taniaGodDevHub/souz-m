<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель таблицы lead_status_history.
 *
 * @property int $id
 * @property int $status_id
 * @property int $lead_id
 * @property int|null $date_add
 *
 * @property LeadStatus $status
 * @property Lead $lead
 */
class LeadStatusHistory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lead_status_history}}';
    }

    public function rules()
    {
        return [
            [['status_id', 'lead_id'], 'required'],
            [['status_id', 'lead_id', 'date_add'], 'integer'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => LeadStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['lead_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lead::class, 'targetAttribute' => ['lead_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_id' => 'Статус',
            'lead_id' => 'Лид',
            'date_add' => 'Дата добавления',
        ];
    }

    public function getStatus()
    {
        return $this->hasOne(LeadStatus::class, ['id' => 'status_id']);
    }

    public function getLead()
    {
        return $this->hasOne(Lead::class, ['id' => 'lead_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert && $this->date_add === null) {
                $this->date_add = time();
            }
            return true;
        }
        return false;
    }
}
