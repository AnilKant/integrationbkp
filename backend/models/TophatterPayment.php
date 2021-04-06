<?php

namespace backend\models;
use common\models\Merchant;

use Yii;

/**
 * This is the model class for table "tophatter_payment".
 *
 * @property integer $id
 * @property integer $merchant_it
 * @property string $plan_type
 * @property string $status
 * @property string $payment_data
 * @property string $billing_on
 * @property string $activated_on
 */
class TophatterPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tophatter_payment';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('admin');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_it', 'plan_type', 'status', 'payment_data', 'billing_on', 'activated_on'], 'required'],
            [['id', 'merchant_it'], 'integer'],
            [['payment_data'], 'string'],
            [['billing_on', 'activated_on'], 'safe'],
            [['plan_type', 'status'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_it' => 'Merchant It',
            'plan_type' => 'Plan Type',
            'status' => 'Status',
            'payment_data' => 'Payment Data',
            'billing_on' => 'Billing On',
            'activated_on' => 'Activated On',
        ];
    }
}
