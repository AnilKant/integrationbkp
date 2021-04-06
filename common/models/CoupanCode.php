<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coupan_code".
 *
 * @property integer $id
 * @property string $promo_code
 * @property string $status
 * @property string $amount_type
 * @property integer $amount
 * @property string $applied_on
 * @property string $expire_date
 * @property string $applied_merchant
 */
class CoupanCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupan_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promo_code', 'status', 'amount_type', 'amount', 'applied_on', 'expire_date', 'applied_merchant'], 'required'],
            [['amount','merchant_id'], 'integer'],
            [['expire_date'], 'safe'],
            [['promo_code', 'status', 'amount_type', 'applied_on', 'applied_merchant'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promo_code' => 'Promo Code',
            'status' => 'Status',
            'amount_type' => 'Amount Type',
            'amount' => 'Amount',
            'applied_on' => 'Applied On',
            'expire_date' => 'Expire Date',
            'applied_merchant' => 'Applied Merchant',
        ];
    }
}
