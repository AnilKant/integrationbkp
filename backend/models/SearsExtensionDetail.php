<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sears_extension_detail".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $install_date
 * @property string $date
 * @property string $expire_date
 * @property string $status
 * @property string $app_status
 * @property string $uninstall_date
 * @property string $panel_username
 * @property string $panel_password
 *
 * @property SearsShopDetails $merchant
 */
class SearsExtensionDetail extends \yii\db\ActiveRecord
{
	public $review_to, $review_from, $live_to, $live_from, $order_to, $order_from ,$shop_url,$shop_name ,$email,$mobile,$config;

    public $user_status;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sears_extension_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'install_date', 'expire_date', 'status'], 'required'],
            [['merchant_id'], 'integer'],
            [['install_date', 'date', 'expire_date', 'uninstall_date'], 'safe'],
            [['status', 'app_status','panel_password','panel_username'], 'string', 'max' => 255],
           /* [['last_login_IP','last_login_time'],'string'],*/
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
            'install_date' => 'Install Date',
            'date' => 'Date',
            'expire_date' => 'Expire Date',
            'status' => 'Purchase Status',
            'app_status' => 'Install Status',
            'uninstall_date' => 'Uninstall Date',
        		'shop_url' => 'Shop Url',
        		'shop_name' => 'Shop Name',
        		'email' => 'Email',
        		'country_code' => 'Country Code',
        		'panel_username' => 'Seller Panel Username',
        		'panel_password' => 'Seller Panel Password',
               /* 'last_login_IP' => 'Last Login IP',
                'last_login_time' => 'Last Login Time',*/
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSears_shop_details()
    {
        return $this->hasOne(SearsShopDetails::className(), ['merchant_id' => 'merchant_id']);
    }
}
