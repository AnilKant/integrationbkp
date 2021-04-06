<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sears_shop_details".
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
 * @property SearsExtensionDetail[] $searsExtensionDetails
 * @property SearsProductFeed[] $searsProductFeeds
 * @property User $merchant
 * @property SearsValueMapping[] $searsValueMappings
 */
class SearsShopDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sears_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'shop_url', 'shop_name', 'email', 'token', 'currency'], 'required'],
            [['merchant_id', 'status'], 'integer'],
            [['shop_url', 'shop_name', 'email', 'token','last_login_IP','last_login_time'], 'string', 'max' => 200],
            [['currency'], 'string', 'max' => 50]
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
            'status' => 'Status',
            'last_login_IP' => 'Last Login IP',
            'last_login_time' => 'Last Login Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSearsExtensionDetails()
    {
        return $this->hasMany(SearsExtensionDetail::className(), ['merchant_id' => 'merchant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSearsProductFeeds()
    {
        return $this->hasMany(SearsProductFeed::className(), ['merchant_id' => 'merchant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(User::className(), ['id' => 'merchant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSearsValueMappings()
    {
        return $this->hasMany(SearsValueMapping::className(), ['merchant_id' => 'merchant_id']);
    }
}
