<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "combo_payment".
 *
 * @property integer $id
 * @property string $shop_url
 * @property string $selected_marketplace
 * @property string $shopify_payment_info
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ComboPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'combo_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_url', 'selected_marketplace', 'status'], 'required'],
            [['shop_url', 'selected_marketplace', 'shopify_payment_info'], 'string'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_url' => 'Shop Url',
            'selected_marketplace' => 'Selected Marketplace',
            'shopify_payment_info' => 'Shopify Payment Info',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
        public static function getDb()
    {
        return Yii::$app->admin;
    }
}
