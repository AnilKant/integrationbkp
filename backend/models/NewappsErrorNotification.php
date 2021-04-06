<?php

namespace backend\models;

use common\models\Merchant;
use Yii;

/**
 * This is the model class for table "error_notification".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $identifier
 * @property string $identifier_type
 * @property string $marketplace
 * @property integer $error_type
 * @property string $reason
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 * @property MerchantDb $merchant
 */
class NewappsErrorNotification extends \yii\db\ActiveRecord
{
    /* Error constant */
    const PRODUCT_CREATE = 1;
    const PRODUCT_UPDATE = 2;
    const PRODUCT_DELETE = 3;
    const PRICE_UPDATE_ERROR = 4;
    const INVENTORY_UPDATE_ERROR = 5;
    const SKU_UPDATE_ERROR = 6;
    const PRODUCT_DELETE_ERROR = 7; //through marketplace
    const ORDER_SHIPMENT = 8;

    /*Error Description Type*/
    const PRODUCT_ERROR = 1;
    const ORDER_ERROR = 2;
    const INVENTORY_ERROR = 3;
    const PRICE_ERROR = 4;
    const RETIRE_ERROR = 5;

    /* Error Identifier Type */
    const SHOPIFY_PRODUCT_ID = 1;
    const SHOPIFY_VARIANT_ID = 2;
    const SKU = 3;
    const PURCHASE_ORDER_ID = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newapps_error_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'required'],
            [['merchant_id', 'error_type'], 'integer'],
            [['reason', 'data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['identifier', 'identifier_type', 'marketplace'], 'string', 'max' => 255]
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
            'identifier' => 'Identifier',
            'identifier_type' => 'Identifier Type',
            'marketplace' => 'Marketplace',
            'error_type' => 'Error Type',
            'reason' => 'Reason',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
    }
    public static function getDb()
    {
        return Yii::$app->admin;
    }

    public static function getErrorTypeNameById($error_type_id){
        switch ($error_type_id) {
            case NewappsErrorNotification::ORDER_SHIPMENT :
                $error_name = 'order_shipment';
                break;
            case NewappsErrorNotification::PRODUCT_UPDATE :
                $error_name = 'product_update';
                break;
            case NewappsErrorNotification::PRODUCT_DELETE :
                $error_name = 'product_delete';
                break;
            case NewappsErrorNotification::PRICE_UPDATE_ERROR :
                $error_name = 'price_update';
                break;
            case NewappsErrorNotification::INVENTORY_UPDATE_ERROR:
                $error_name = 'inventory';
                break;
            case NewappsErrorNotification::SKU_UPDATE_ERROR:
                $error_name = 'sku_update';
                break;
            case NewappsErrorNotification::PRODUCT_DELETE_ERROR:
                $error_name = 'marketplace_delete_error';
                break;
            default:
                $error_name = 'product_create';
                break;
        }
        return $error_name;
    }

    public function getErrorTypeIdByName($error_name){
        switch ($error_name){
            default:
                $error_id = self::PRODUCT_CREATE;
                break;
        }
        return $error_id;
    }

    public static function getErrorTypeArray(){
        return [
            self::PRODUCT_CREATE => self::getErrorTypeNameById(self::PRODUCT_CREATE),
            self::PRODUCT_UPDATE => self::getErrorTypeNameById(self::PRODUCT_UPDATE),
            self::PRODUCT_DELETE => self::getErrorTypeNameById(self::PRODUCT_DELETE),
            self::PRICE_UPDATE_ERROR => self::getErrorTypeNameById(self::PRICE_UPDATE_ERROR),
            self::INVENTORY_UPDATE_ERROR => self::getErrorTypeNameById(self::INVENTORY_UPDATE_ERROR),
            self::SKU_UPDATE_ERROR => self::getErrorTypeNameById(self::SKU_UPDATE_ERROR),
            self::PRODUCT_DELETE_ERROR => self::getErrorTypeNameById(self::PRODUCT_DELETE_ERROR),
            self::ORDER_SHIPMENT => self::getErrorTypeNameById(self::ORDER_SHIPMENT),
        ];
    }
}
