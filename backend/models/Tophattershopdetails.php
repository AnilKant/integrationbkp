<?php

namespace backend\models;

use Yii;
use common\models\Merchant;

/**
 * This is the model class for table "tophatter_shop_details".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $token
 * @property integer $install_status
 * @property string $install_date
 * @property string $uninstall_dates
 * @property integer $purchase_status
 * @property string $expire_date
 * @property string $created_at
 * @property string $updated_at
 */
class Tophattershopdetails extends \yii\db\ActiveRecord
{
    public $config;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tophatter_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'install_date'], 'required'],
            [['merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['install_date', 'uninstall_dates', 'expire_date', 'created_at', 'updated_at'], 'safe'],
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
            'uninstall_dates' => 'Uninstall Dates',
            'purchase_status' => 'Purchase Status',
            'expire_date' => 'Expire Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
    }
    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
