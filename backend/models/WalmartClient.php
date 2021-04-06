<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "walmart_client".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $seller_store_name
 * @property string $email
 * @property string $phone
 * @property string $country
 * @property integer $code
 * @property string $annual_revenue
 * @property string $website
 * @property string $shipping_source
 * @property string $total_skus
 * @property string $company_address
 * @property string $valid_tax_w9
 * @property string $warehouse_in_usa
 * @property string $type_product
 * @property string $selling_marketplace
 * @property string $different_channel_partner
 * @property string $others
 * @property string $walmart_contact_before
 * @property string $walmart_approved
 * @property string $amazon_sellerurl
 * @property integer $is_activated
 * @property string $company_name
 * @property string $other_framework
 * @property string $integration_framework
 * @property string $position
 */
class WalmartClient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'walmart_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'seller_store_name', 'email', 'phone', 'country', 'code', 'annual_revenue', 'website', 'shipping_source', 'total_skus', 'company_address', 'valid_tax_w9', 'warehouse_in_usa', 'type_product', 'selling_marketplace', 'different_channel_partner', 'others', 'walmart_contact_before', 'walmart_approved', 'amazon_sellerurl'], 'required'],
            [['firstname', 'lastname', 'seller_store_name', 'email', 'phone', 'country', 'annual_revenue', 'website', 'shipping_source', 'total_skus', 'company_address', 'valid_tax_w9', 'warehouse_in_usa', 'type_product', 'selling_marketplace', 'different_channel_partner', 'others', 'walmart_contact_before', 'walmart_approved', 'amazon_sellerurl'], 'string'],
            [['code', 'is_activated'], 'integer'],
            [['company_name', 'position'], 'string', 'max' => 40],
            [['other_framework', 'integration_framework'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'seller_store_name' => 'Seller Store Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'country' => 'Country',
            'code' => 'Code',
            'annual_revenue' => 'Annual Revenue',
            'website' => 'Website',
            'shipping_source' => 'Shipping Source',
            'total_skus' => 'Total Skus',
            'company_address' => 'Company Address',
            'valid_tax_w9' => 'Valid Tax W9',
            'warehouse_in_usa' => 'Warehouse In Usa',
            'type_product' => 'Type Product',
            'selling_marketplace' => 'Selling Marketplace',
            'different_channel_partner' => 'Different Channel Partner',
            'others' => 'Others',
            'walmart_contact_before' => 'Walmart Contact Before',
            'walmart_approved' => 'Walmart Approved',
            'amazon_sellerurl' => 'Amazon Sellerurl',
            'is_activated' => 'Is Activated',
            'company_name' => 'Company Name',
            'other_framework' => 'Other Framework',
            'integration_framework' => 'Integration Framework',
            'position' => 'Position',
        ];
    }
}
