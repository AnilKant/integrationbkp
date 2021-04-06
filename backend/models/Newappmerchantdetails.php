<?php

namespace backend\models;

use Yii;
class Newappmerchantdetails extends \yii\db\ActiveRecord
{

    public $user_status;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'merchant_db';
    }

        public static function getDb()
    {
        return Yii::$app->admin;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'required'],
            [['shop_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'shop_name' => 'Shop Name',
            
        ];
    }


}