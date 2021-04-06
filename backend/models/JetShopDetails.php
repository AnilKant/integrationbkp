<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "jet_shop_details".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $shop_url
 * @property string $shop_name
 * @property string $email
 * @property string $country_code
 * @property string $currency
 * @property string $install_status
 * @property string $installed_on
 * @property string $expired_on
 * @property string $purchased_on
 * @property string $purchase_status
 * @property string $sendmail
 */
class JetShopDetails extends \yii\db\ActiveRecord
{
    public $review_to, $review_from, $live_to, $live_from, $order_to, $order_from, $user_status,$mobile,$config;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jet_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'shop_url', 'install_status', 'installed_on', 'expired_on', 'purchase_status'], 'required'],
            [['merchant_id'], 'integer'],
            [['installed_on', 'expired_on', 'purchased_on','sendmail','seller_username','seller_password','last_login','prod_import_limit'], 'safe'],
            [['shop_url', 'shop_name', 'country_code'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['currency', 'install_status'], 'string', 'max' => 40],
            [['purchase_status'], 'string', 'max' => 100],
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
            'shop_name' => 'Shop Name',
            'email' => 'Email',
            'country_code' => 'Country Code',
            'currency' => 'Currency',
            'install_status' => 'Install Status',
            'installed_on' => 'Installed On',
            'expired_on' => 'Expired On',
            'purchased_on' => 'Purchased On',
            'purchase_status' => 'Purchase Status',
            'sendmail' => 'Is Send Mail',
            'last_login' => 'last visit',
            'ip_address' => 'ip address',
        ];
    }
}