<?php

namespace app\modules\cars\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "car_model".
 *
 * @property int $id
 * @property int $mark_id ID марки
 * @property string $name Название модели (латиница)
 * @property string|null $name_cyrillic Название модели (кириллица)
 * @property string|null $class Класс автомобиля
 * @property int|null $year_from Год начала производства модели
 * @property int|null $year_to Год окончания производства модели
 * @property int|null $created_at Дата создания
 * @property int|null $updated_at Дата обновления
 *
 * @property CarMark $mark
 */
class CarModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%car_model}}';
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
            [['mark_id', 'name'], 'required'],
            [['mark_id', 'year_from', 'year_to', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_cyrillic'], 'string', 'max' => 255],
            [['class'], 'string', 'max' => 10],
            [['mark_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarMark::className(), 'targetAttribute' => ['mark_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mark_id' => 'Марка',
            'name' => 'Название модели (латиница)',
            'name_cyrillic' => 'Название модели (кириллица)',
            'class' => 'Класс автомобиля',
            'year_from' => 'Год начала производства',
            'year_to' => 'Год окончания производства',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Связь с маркой
     * @return \yii\db\ActiveQuery
     */
    public function getMark()
    {
        return $this->hasOne(CarMark::className(), ['id' => 'mark_id']);
    }
}

