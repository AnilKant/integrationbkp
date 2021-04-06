<?php
namespace backend\models;

use Yii;

class GrouponShopDetails extends \yii\db\ActiveRecord
{    
    public $shopurl;
    public $owner_name;
    public $email;
    public $user_status;
    public $config;
    public $shop_json;

    const PURCHASE_STATUS_TRAIL = 'trail';
    const PURCHASE_STATUS_TRAILEXPIRE = 'trail-expired';
    const PURCHASE_STATUS_PURCHASED = 'purchased';
    const PURCHASE_STATUS_LICENSEEXPIRE = 'license-expired';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groupon_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'install_date'], 'required'],
            [['merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['install_date', 'uninstall_date', 'expire_date', 'updated_at', 'created_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
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
            'uninstall_date' => 'Uninstall Date',
            'purchase_status' => 'Purchase Status',
            'expire_date' => 'Expire Date',
            /*'ip_address' => 'Ip Address',
            'last_login' => 'Last Login',*/
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
