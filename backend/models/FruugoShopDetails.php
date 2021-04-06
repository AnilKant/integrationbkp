<?php

namespace backend\models;

use Yii;
use common\models\Merchant;

/**
 * This is the model class for table "fruugo_shop_details".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $shop_url
 * @property string $shop_name
 * @property string $email
 * @property string $token
 * @property string $currency
 * @property integer $status
 *
 * @property User $merchant
 */
class FruugoShopDetails extends \yii\db\ActiveRecord
{
    public $mobile;
    public $config;
    public $verified_email;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fruugo_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id','expire_date','allowed_sku', 'shop_url', 'shop_name', 'email', 'token'], 'required'],
            [['merchant_id','allowed_sku'], 'integer'],
            [['shop_url', 'shop_name','purchase_status', 'email', 'token'], 'string', 'max' => 200],
            //[['currency'], 'string', 'max' => 50],
            
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
            //'currency' => 'Currency',
            'purchase_status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
    }
    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
