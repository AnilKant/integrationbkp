<?php

namespace backend\models;

use Yii;
use backend\components\onbuy\TableConstant;

/**
 * This is the model class for table "catch_shop_details".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $token
 * @property integer $install_status
 * @property string $install_date
 * @property string $uninstall_dates
 * @property integer $purchase_status
 * @property string $expire_date
 * @property string $ip_address
 * @property string $last_login
 * @property string $updated_at
 * @property string $created_at
 *
 * @property MerchantDb $merchant
 */
class OnbuyShopDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $shopurl;
    public $email;
    public $verified_mobile;
    public $status;
    public $owner_name;
    public $barcode_exempted;
    // public $lname;
    // public $seller_username;
    // public $seller_password;

    public static function tableName()
    {
        return TableConstant::SHOP_DETAILS;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'install_date'], 'required'],
            [['merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['install_date', 'uninstall_date', 'expire_date', 'last_login', 'updated_at', 'created_at'], 'safe'],
            [['token', 'ip_address'], 'string', 'max' => 255],
            //[['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => MerchantDb::className(), 'targetAttribute' => ['merchant_id' => 'merchant_id']],
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
            'token' => 'Token',
            'install_status' => 'Install Status',
            'install_date' => 'Install Date',
            'uninstall_dates' => 'Uninstall Dates',
            'purchase_status' => 'Purchase Status',
            'expire_date' => 'Expire Date',
            'ip_address' => 'Ip Address',
            'last_login' => 'Last Login',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(MerchantDb::className(), ['merchant_id' => 'merchant_id']);
    }

    public static function getDb()
    {
        return Yii::$app->get('admin');
    }

}
