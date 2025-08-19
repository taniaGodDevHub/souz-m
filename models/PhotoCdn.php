<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "photo_cdn".
 *
 * @property int $id
 * @property string|null $ex_link
 * @property string|null $in_link
 */
class PhotoCdn extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'photo_cdn';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ex_link', 'in_link'], 'default', 'value' => null],
            [['ex_link', 'in_link'], 'string', 'max' => 2500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ex_link' => 'Ex Link',
            'in_link' => 'In Link',
        ];
    }

}
