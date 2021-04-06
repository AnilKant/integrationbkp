<?php

namespace backend\models;

use Yii;
use common\models\Merchant;

/**
 * This is the model class for table "etsy_shop_details".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $token
 * @property string $install_status
 * @property string $install_date
 * @property string $uninstall_date
 * @property string $purchase_status
 * @property string $last_login_ip
 * @property string $last_login_time
 * @property string $expire_date
 * @property string $created_at
 * @property string $updated_at
 * @property integer $product_limit
 * @property integer $sku_upload_limit
 * @property integer $limit_renew_till
 * @property integer $product_count
 * @property integer $order_count
 *
 * @property MerchantDb $merchant
 */
class EtsyShopDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'etsy_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'product_limit','sku_upload_limit','do_not_contact','is_digital_allowed'], 'integer'],
            [['install_date', 'uninstall_date', 'last_login_time', 'expire_date', 'created_at', 'updated_at','limit_renew_till','do_not_contact','product_count','order_count'], 'safe'],
            [['token', 'install_status', 'purchase_status', 'last_login_ip'], 'string', 'max' => 255],
           // [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => MerchantDb::className(), 'targetAttribute' => ['merchant_id' => 'merchant_id']],
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
            'last_login_ip' => 'Last Login Ip',
            'last_login_time' => 'Last Login Time',
            'expire_date' => 'Expire Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'product_limit' => 'Product Limit',
            'product_count' => 'Product Count',
            'order_count' => 'Order Count',
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
