<?php
namespace backend\models;
use Yii;

/**
 * This is the model class for table "magento_extension_detail".
 *
 * @property integer $id
 * @property string $email
 * @property string $store_url
 * @property string $total_product
 * @property string $published
 * @property string $unpublished
 * @property string $total_order
 * @property string $complete_orders
 * @property string $config_set
 * @property string $pilot_seller
 * @property string $plateform
 * @property string $ac_details
 */
class MagentoExtensionDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const IP="66.117.14.126";
    const USER="cedcom5";
    const PASSWORD="Wbyi7m8e";
    const PORT=2083;
    const DOMAIN='cedcommerce.com';
    const EMAIL_QUOTA=50;

    public static function tableName()
    {
        return 'magento_extension_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'store_url'], 'required'],
            [['store_url','total_product','published','unpublished','total_order','complete_orders','config_set','plateform','pilot_seller','ac_details'], 'string'],
            [['email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'store_url' => 'Shop Url',
            'total_order' => 'Total Order',
            'complete_orders' => 'Completed Orders',
            'total_product' => 'Total Product',
            'pilot_seller' => 'Pilot Seller',
            'plateform' =>'Plateform',
        ];
    }
}
