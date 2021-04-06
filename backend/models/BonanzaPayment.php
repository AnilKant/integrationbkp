<?php

namespace backend\models;

use Yii;
/**
 * This is the model class for table "bonanza_payment".
 *
 * @property integer $id
 * @property integer $charge_id
 * @property integer $merchant_id
 * @property string $billing_on
 * @property string $activated_on
 * @property string $plan_type
 * @property string $status
 * @property string $recurring_data
 *
 * @property BonanzaShopDetails $merchant
 */
class BonanzaPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bonanza_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge_id', 'merchant_id'], 'integer'],
            [['merchant_id', 'billing_on', 'activated_on', 'recurring_data'], 'required'],
            [['billing_on', 'activated_on'], 'safe'],
            [['recurring_data'], 'string'],
            [['plan_type'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 255],
            [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => BonanzaShopDetails::className(), 'targetAttribute' => ['merchant_id' => 'merchant_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'charge_id' => 'Charge ID',
            'merchant_id' => 'Merchant ID',
            'billing_on' => 'Billing On',
            'activated_on' => 'Activated On',
            'plan_type' => 'Plan Type',
            'status' => 'Status',
            'recurring_data' => 'Recurring Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(BonanzaShopDetails::className(), ['merchant_id' => 'merchant_id']);
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
