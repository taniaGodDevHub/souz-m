<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель таблицы lead_files.
 *
 * @property int $id
 * @property int $lead_id
 * @property string $name
 * @property string|null $extention
 * @property string $file_path
 *
 * @property Lead $lead
 */
class LeadFile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lead_files}}';
    }

    public function rules()
    {
        return [
            [['lead_id', 'name', 'file_path'], 'required'],
            [['lead_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['extention'], 'string', 'max' => 5],
            [['file_path'], 'string', 'max' => 1500],
            [['lead_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lead::class, 'targetAttribute' => ['lead_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lead_id' => 'Лид',
            'name' => 'Имя файла',
            'extention' => 'Расширение',
            'file_path' => 'Путь до файла',
        ];
    }

    public function getLead()
    {
        return $this->hasOne(Lead::class, ['id' => 'lead_id']);
    }
}
