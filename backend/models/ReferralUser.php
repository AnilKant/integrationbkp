<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "referral_user".
 *
 * @property integer $id
 * @property integer $referrer_id
 * @property integer $merchant_id
 * @property string $app
 * @property string $status
 * @property string $installation_date
 * @property string $payment_date
 */
class ReferralUser extends \yii\db\ActiveRecord
{
    public $user_username;
    public $user_shopname;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referral_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['referrer_id', 'merchant_id', 'app', 'status'], 'required'],
            [['referrer_id', 'merchant_id'], 'integer'],
            [['installation_date', 'payment_date'], 'safe'],
            [['app'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'referrer_id' => 'Referrer ID',
            'merchant_id' => 'Referral Merchant ID',
            'app' => 'App',
            'status' => 'Status',
            'installation_date' => 'Installation Date',
            'payment_date' => 'Payment Date',
        ];
    }
}
