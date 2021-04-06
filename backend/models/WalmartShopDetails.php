<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "walmart_shop_details".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $shop_url
 * @property string $shop_name
 * @property string $email
 * @property string $token
 * @property string $currency
 * @property integer $status
 */
class WalmartShopDetails extends \yii\db\ActiveRecord
{
    const PRODUCT_STATUS_UPLOADED = 'PUBLISHED';
    const PRODUCT_STATUS_UNPUBLISHED = 'UNPUBLISHED';
    const PRODUCT_STATUS_STAGE = 'STAGE';
    const PRODUCT_STATUS_NOT_UPLOADED = 'Not Uploaded';
    const PRODUCT_STATUS_PROCESSING = 'Item Processing';
    const PRODUCT_STATUS_PARTIAL_UPLOADED = 'PARTIAL UPLOADED';

    public $user_status;
    public $config;
    public $mobile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'walmart_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'shop_url', 'shop_name', 'email', 'token', 'currency'], 'required'],
            [['merchant_id', 'status'], 'integer'],
            [['shop_url', 'shop_name', 'email', 'token','seller_username','seller_password'], 'string', 'max' => 200],
            [['last_login'], 'safe'],
            [['currency','ip_address','install_country'], 'string', 'max' => 50]
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
            'currency' => 'Currency',
            'status' => 'App Status',
            'ip_address' => 'ip',
            'last_login' => 'Last Login',
            'install_country' => 'Country'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalmartExtensionDetail()
    {
        return $this->hasOne(WalmartExtensionDetail::className(), ['merchant_id' => 'merchant_id']);
    }
}
