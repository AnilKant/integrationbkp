<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "newegg_shop_detail".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $shop_url
 * @property string $shop_name
 * @property string $email
 * @property string $token
 * @property string $country_code
 * @property string $currency
 * @property string $install_status
 * @property string $install_date
 * @property string $expire_date
 * @property string $purchase_date
 * @property string $purchase_status
 * @property string $client_data
 * @property string $uninstall_date
 * @property string $app_status
 *
 * @property User $merchant
 */
class NeweggCanShopDetail extends \yii\db\ActiveRecord
{
    public $user_status;
    public $config;
    public $mobile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newegg_can_shop_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'shop_url', 'token', 'currency', 'install_status', 'install_date', 'expire_date', 'purchase_status'], 'required'],
            [['merchant_id'], 'integer'],
            [['shop_url', 'token', 'client_data'], 'string'],
            [['install_date', 'expire_date', 'purchase_date', 'uninstall_date'], 'safe'],
            [['shop_name', 'country_code'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['currency', 'install_status', 'purchase_status'], 'string', 'max' => 40],
            [['app_status'], 'string', 'max' => 45]
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
            'token' => 'Token',
            'country_code' => 'Country Code',
            'currency' => 'Currency',
            'install_status' => 'Install Status',
            'install_date' => 'Install Date',
            'expire_date' => 'Expire Date',
            'purchase_date' => 'Purchase Date',
            'purchase_status' => 'Purchase Status',
            'client_data' => 'Client Data',
            'uninstall_date' => 'Uninstall Date',
            'app_status' => 'App Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(User::className(), ['id' => 'merchant_id']);
    }
}
