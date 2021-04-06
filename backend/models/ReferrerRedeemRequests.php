<?php

namespace backend\models;

use Yii;
use backend\components\Data;
use backend\components\Referral;

/**
 * This is the model class for table "referrer_redeem_requests".
 *
 * @property integer $id
 * @property integer $referrer_id
 * @property string $amount
 * @property string $redeem_method
 * @property string $data
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class ReferrerRedeemRequests extends \yii\db\ActiveRecord
{
    public $merchant_id;
    public $user_username;
    public $user_shopname;
    public $referrer_username;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referrer_redeem_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['referrer_id', 'amount', 'redeem_method', 'data'], 'required'],
            [['referrer_id'], 'integer'],
            [['amount'], 'number'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['redeem_method', 'status'], 'string', 'max' => 20]
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
            'amount' => 'Amount',
            'redeem_method' => 'Redeem Method',
            'data' => 'Data',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function confirm()
    {
        if($this->redeem_method == 'paypal')
        {
            $this->status = 'complete';
            $this->save();

            $payment_data = json_decode($this->data, true);
            $referrerPaymentId = $payment_data['referrer_payment_id'];
            $status = 'complete';
            $query = "UPDATE `referrer_payment` SET `status`='{$status}' WHERE `id`='{$referrerPaymentId}'";
            Data::sqlRecords($query, null, 'update');

            return true;
        }
        elseif($this->redeem_method == 'subscription')
        {
            $payment_data = json_decode($this->data, true);
            if(isset($payment_data['months'])) 
            {
                $months = intval($payment_data['months']);
                $app = $payment_data['app'];

                $merchant_id = Referral::getMerchantIdFromReferrerId($this->referrer_id);

                if($merchant_id)
                {
                    $query = '';
                    $status = 'Purchased';
                    switch ($app) {
                        case 'jet':
                            $query = "UPDATE `jet_extension_detail` SET `expire_date` = CASE WHEN `expire_date` < NOW()   THEN DATE_ADD(NOW(), INTERVAL ".$months." MONTH) ELSE DATE_ADD(expire_date,INTERVAL ".$months." MONTH) END , `status` = '{$status}' WHERE `merchant_id` = '{$merchant_id}'";
                            break;
                        
                        case 'walmart':
                            $query = "UPDATE `walmart_extension_detail` SET `expire_date` = CASE WHEN `expire_date` < NOW()   THEN DATE_ADD(NOW(), INTERVAL ".$months." MONTH) ELSE DATE_ADD(expire_date,INTERVAL ".$months." MONTH) END , `status` = '{$status}' WHERE `merchant_id` = '{$merchant_id}'";
                            break;

                        case 'newegg':
                            $query = "UPDATE `newegg_shop_detail` SET `expire_date` = CASE WHEN `expire_date` < NOW()   THEN DATE_ADD(NOW(), INTERVAL ".$months." MONTH) ELSE DATE_ADD(expire_date,INTERVAL ".$months." MONTH) END , `purchase_status` = '{$status}' WHERE `merchant_id` = '{$merchant_id}'";
                            break;
                    }

                    if($query != '')
                    {
                        Data::sqlRecords($query, null, 'update');

                        $this->status = 'complete';
                        $this->save();

                        $referrerPaymentId = $payment_data['referrer_payment_id'];
                        $status = 'complete';
                        $query = "UPDATE `referrer_payment` SET `status`='{$status}' WHERE `id`='{$referrerPaymentId}'";
                        Data::sqlRecords($query, null, 'update');
                        
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
