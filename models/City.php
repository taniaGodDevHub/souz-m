<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property int $accept
 * @property string $name
 */
class City extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['accept'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'accept' => 'Отображать',
        ];
    }

    /**
     * Получить список городов для dropdown
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(City::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }

    /**
     * Связь с дилерскими центрами
     * @return \yii\db\ActiveQuery
     */
    public function getDealers()
    {
        return $this->hasMany(Dealers::className(), ['city_id' => 'id']);
    }

}
