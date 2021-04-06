<?php

namespace backend\models;

use Yii;
use common\models\Merchant;
use backend\models\PricefallsInstallation;

/**
 * This is the model class for table "pricefalls_shop_details".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $token
 * @property string $install_status
 * @property string $install_date
 * @property string $uninstall_date
 * @property string $uninstall_status
 * @property string $expire_date
 * @property string $purchase_status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property MerchantDb $merchant
 */
class Pricefallsshopdetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pricefalls_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'integer'],
            [['install_date', 'uninstall_date', 'expire_date', 'last_login_IP', 'last_login_time', 'created_at', 'updated_at'], 'safe'],
            [['token', 'install_status', 'purchase_status'], 'string', 'max' => 255],
            
        ];
        /* return [
            [['merchant_id', 'install_date'], 'required'],
            [['merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['install_date', 'uninstall_date', 'expire_date', 'created_at', 'updated_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
        ];*/
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
            /*'uninstall_status' => 'Uninstall Status',*/
            'expire_date' => 'Expire Date',
            'purchase_status' => 'Purchase Status',
            'last_login_IP' => 'Last Login IP',
            'last_login_time' => 'Last Login Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
    }
    
    public function getInstallationdetail()
    {
        return $this->hasOne(PricefallsInstallation::className(),['merchant_id' => 'merchant_id']);
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
