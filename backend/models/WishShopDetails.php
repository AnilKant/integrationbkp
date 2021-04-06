<?php
namespace backend\models;

use Yii;
use common\models\Merchant;

class WishShopDetails extends \yii\db\ActiveRecord
{    
    public $shopurl;
    public $owner_name;
    public $email;
    public $user_status;
    public $config;
    public $shop_json;
    public $country_code;
    public $verified_mobile;
    public $plan_type;
    public $already_selling;
    public $wish_mid;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wish_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'install_date'], 'required'],
            [['merchant_id', 'install_status', 'purchase_status','sku_upload_limit','sku_import_limit'], 'integer'],
            [['install_date', 'uninstall_dates', 'expire_date', 'last_login', 'updated_at', 'created_at'], 'safe'],
            [['token', 'ip_address'], 'string', 'max' => 255],
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
            'sku_upload_limit' => 'SKU upload limit',
            'sku_import_limit' => 'SKU import limit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalmartExtensionDetail()
    {
        return $this->hasOne(WalmartExtensionDetail::className(), ['merchant_id' => 'merchant_id']);
    }

    public static function getDb()
    {
        return Yii::$app->get('admin');
    }
}
