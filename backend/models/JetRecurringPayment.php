<?php

namespace backend\models;
use common\models\User;
use common\models\JetExtensionDetail;


use Yii;

/**
 * This is the model class for table "jet_recurring_payment".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $billing_on
 * @property string $activated_on
 * @property string $plan_type
 * @property string $status
 * @property string $recurring_data
 *
 * @property User $merchant
 */
class JetRecurringPayment extends \yii\db\ActiveRecord
{
    public $expired_on;
    public $expired_on_to;
    public $expired_on_from;
    public $activated_on_to;
    public $activated_on_from;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jet_recurring_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'billing_on', 'activated_on', 'plan_type', 'status', 'recurring_data'], 'required'],
            [['id', 'merchant_id'], 'integer'],
            [['billing_on', 'activated_on'], 'safe'],
            [['recurring_data'], 'string'],
            [['plan_type'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 255]
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
   /*  public function getMerchant()
    {
        return $this->hasOne(User::className(), ['id' => 'merchant_id']);
    } */
    public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'merchant_id']);
    }
    
    public function getJet_shop_details()
    {
        return $this->hasOne(JetShopDetails::className(), ['merchant_id' => 'merchant_id']);
    }
}
