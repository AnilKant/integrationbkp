<?php

namespace backend\models;

use Yii;
/**
 * This is the model class for table "etsy_payment".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $billing_on
 * @property string $activated_on
 * @property string $plan_type
 * @property string $status
 * @property string $payment_data
 * @property string $plan_features
 *
 * @property EtsyShopDetails $merchant
 */
class EtsyPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'etsy_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['merchant_id', 'billing_on', 'activated_on', 'payment_data'], 'required'],
            [['billing_on', 'activated_on'], 'safe'],
            [['payment_data','plan_features'], 'string'],
            [['plan_type'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 255],
            [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => EtsyShopDetails::className(), 'targetAttribute' => ['merchant_id' => 'merchant_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Charge ID',
            'merchant_id' => 'Merchant ID',
            'billing_on' => 'Billing On',
            'activated_on' => 'Activated On',
            'plan_type' => 'Plan Type',
            'status' => 'Status',
            'payment_data' => 'Payment Details',
            'plan_features' => 'Plan Features',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(EtsyShopDetails::className(), ['merchant_id' => 'merchant_id']);
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
