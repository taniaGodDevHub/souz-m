<?php

namespace app\modules\cars\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "car_mark".
 *
 * @property int $id
 * @property string $name Название марки (латиница)
 * @property string|null $name_cyrillic Название марки (кириллица)
 * @property int $is_popular Популярная марка
 * @property string|null $country Страна
 * @property int|null $year_from Год начала производства марки
 * @property int|null $year_to Год окончания производства марки
 * @property int|null $created_at Дата создания
 * @property int|null $updated_at Дата обновления
 *
 * @property CarModel[] $models
 */
class CarMark extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%car_mark}}';
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
            [['name'], 'required'],
            [['is_popular', 'year_from', 'year_to', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_cyrillic', 'country'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название марки (латиница)',
            'name_cyrillic' => 'Название марки (кириллица)',
            'is_popular' => 'Популярная марка',
            'country' => 'Страна',
            'year_from' => 'Год начала производства',
            'year_to' => 'Год окончания производства',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Связь с моделями
     * @return \yii\db\ActiveQuery
     */
    public function getModels()
    {
        return $this->hasMany(CarModel::className(), ['mark_id' => 'id']);
    }

    /**
     * Получить список марок для выпадающих списков
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(self::find()->orderBy('name')->all(), 'id', 'name');
    }

    /**
     * Получить количество моделей у марки
     * @return int
     */
    public function getModelsCount()
    {
        return $this->getModels()->count();
    }
}

