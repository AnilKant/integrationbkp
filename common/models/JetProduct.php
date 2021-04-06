<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "jet_product".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $title
 * @property string $sku
 * @property string $description
 * @property integer $variant_id
 * @property string $image
 * @property integer $qty
 * @property double $price
 * @property string $attr_ids
 * @property string $jet_attributes
 * @property string $vendor
 * @property string $upc
 * @property string $brand
 * @property string $ASIN
 * @property string $manufacturer_part_number
 * @property integer $fulfillment_node
 * @property string $status
 */
class JetProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jet_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'variant_id'], 'required'],
            [['id', 'merchant_id', 'variant_id', 'qty', 'fulfillment_node'], 'integer'],
            [['price'], 'number'],
            [['attr_ids','barcode_type', 'jet_attributes','description','status','image','error'], 'string'],
            [['title', 'sku', 'vendor', 'ASIN','type'], 'string', 'max' => 255],
            [['upc'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'title' => 'Title',
            'sku' => 'Sku',
            'description' => 'Description',
            'variant_id' => 'Variant ID',
            'image' => 'Image',
            'qty' => 'Qty',
            'price' => 'Price',
            'attr_ids' => 'Attr Ids',
            'jet_attributes' => 'Jet Attributes',
            'vendor' => 'Brand',
            'type' => 'Type',
            'product_type'=>'Product Type',
            'upc' => 'Barcode',
            //'brand' => 'Brand',
            'ASIN' => 'Asin',
            'barcode_type'=>'Barcode Type',
            //'product_data'=>"Product Data",
            //'manufacturer_part_number' => 'Manufacturer Part Number',
            'fulfillment_node' => 'Fulfillment Node',
            'status' => 'Status',
            'error'=>'Upload Error',
        ];
    }
}
