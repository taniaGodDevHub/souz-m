<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "souz_product".
 *
 * @property int $id
 * @property int $ex_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $sku
 * @property string|null $excerpt
 * @property string|null $price
 * @property string|null $regular_price
 * @property string|null $sale_price
 * @property string|null $stock_status
 * @property string|null $categories
 * @property string|null $subcategory
 * @property string|null $thumbnail_url
 * @property string|null $gallery
 * @property string|null $permalink
 * @property string|null $attributes
 * @property int|null $date_update
 * @property string|null $thumbnail_url_medium
 */
class SouzProduct extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'souz_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'sku', 'excerpt', 'price', 'regular_price', 'sale_price', 'stock_status', 'categories', 'subcategory', 'thumbnail_url', 'gallery', 'permalink', 'attributes', 'date_update'], 'default', 'value' => null],
            [['ex_id'], 'required'],
            [['ex_id', 'date_update'], 'integer'],
            [['description', 'excerpt', 'title'], 'string'],
            [['sku', 'price', 'regular_price', 'sale_price', 'stock_status'], 'string', 'max' => 255],
            [['attributes', 'gallery', 'thumbnail_url', 'permalink', 'categories', 'subcategory'], 'string', 'max' => 3000],
            [['thumbnail_url_medium'], 'string', 'max' => 1500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ex_id' => 'Ex ID',
            'description' => 'description',
            'sku' => 'Sku',
            'excerpt' => 'Excerpt',
            'price' => 'Price',
            'regular_price' => 'Regular Price',
            'sale_price' => 'Sale Price',
            'stock_status' => 'Stock Status',
            'categories' => 'Categories',
            'subcategory' => 'Subcategory',
            'thumbnail_url' => 'Thumbnail Url',
            'gallery' => 'Gallery',
            'permalink' => 'Permalink',
            'attributes' => 'Attributes',
            'date_update' => 'Date Update',
            'thumbnail_url_medium' => 'thumbnail_url_medium',
        ];
    }

}
