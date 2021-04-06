<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "shop_erasure_data".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $shop_url
 * @property string $mobile
 * @property string $email
 * @property string $marketplace
 * @property integer $total_products
 * @property integer $total_orders
 * @property integer $total_revenue
 * @property string $config_set
 * @property string $purchased_status
 * @property string $install_date
 * @property string $uninstall_date
 * @property string $last_purchased_plan
 * @property string $shopify_plan_name
 * @property string $shop_data
 * @property string $marketplace_configuration
 * @property string $token
 * @property string $expiry_date
 */
class ShopErasureData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_erasure_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'shop_url'], 'required'],
            [['merchant_id', 'total_products', 'total_orders', 'total_revenue'], 'integer'],
            [['install_date', 'uninstall_date', 'expiry_date'], 'safe'],
            [['shop_data', 'marketplace_configuration'], 'string'],
            [['shop_url'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 100],
            [['email', 'marketplace', 'config_set', 'purchased_status', 'last_purchased_plan', 'shopify_plan_name'], 'string', 'max' => 50],
            [['token'], 'string', 'max' => 225],
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
            'shop_url' => 'Shop Url',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'marketplace' => 'Marketplace',
            'total_products' => 'Total Products',
            'total_orders' => 'Total Orders',
            'total_revenue' => 'Total Revenue',
            'config_set' => 'Config Set',
            'purchased_status' => 'Purchased Status',
            'install_date' => 'Install Date',
            'uninstall_date' => 'Uninstall Date',
            'last_purchased_plan' => 'Last Purchased Plan',
            'shopify_plan_name' => 'Shopify Plan Name',
            'shop_data' => 'Shop Data',
            'marketplace_configuration' => 'Marketplace Configuration',
            'token' => 'Token',
            'expiry_date' => 'Expiry Date',
        ];
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
