<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "jet_extension_detail".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $date
 * @property string $expire_date
 * @property string $email
 * @property string $shopurl
 * @property integer $merchant_id
 * @property integer $status
 */
class WalmartExtensionDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'walmart_extension_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'expire_date', 'merchant_id', 'status'], 'required'],
            [['order_id', 'merchant_id'], 'integer'],
            [['install_date','date', 'expire_date'], 'safe'],
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
        	'install_date'=>'Install Date',
            'date' => 'Date',
            'expire_date' => 'Expire Date',
            'merchant_id' => 'Merchant ID',
            'status' => 'Status'
        ];
    }
}
