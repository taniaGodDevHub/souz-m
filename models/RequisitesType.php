<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Модель таблицы requisites_type.
 *
 * @property int $id
 * @property string $name
 * @property int $sort
 *
 * @property Requisites[] $requisites
 */
class RequisitesType extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%requisites_type}}';
    }

    public function rules()
    {
        return [
            [['name', 'sort'], 'required'],
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'sort' => 'Сортировка',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->orderBy(['sort' => SORT_ASC])->all(), 'id', 'name');
    }

    public function getRequisites()
    {
        return $this->hasMany(Requisites::class, ['requisites_type_id' => 'id']);
    }
}
