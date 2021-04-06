<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "walmart_extension_detail".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $install_date
 * @property string $date
 * @property string $expire_date
 * @property string $status
 *
 * @property User $merchant
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
            [['merchant_id', 'install_date', 'expire_date', 'status','uninstall_date'], 'required'],
            [['merchant_id'], 'integer'],
            [['install_date', 'date', 'expire_date','uninstall_date'], 'safe'],
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
            'install_date' => 'Install Date',
            'date' => 'Date',
            'expire_date' => 'Expire Date',
            'status' => 'Status',
            'uninstall_date'=>'Unistall Date'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(User::className(), ['id' => 'merchant_id']);
    }
}
