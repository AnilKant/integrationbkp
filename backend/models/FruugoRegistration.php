<?php
namespace backend\models;

use Yii;
use common\models\Merchant;

/**
 * This is the model class for table "fruugo_registration".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $fname
 * @property string $lname
 * @property string $store_name
 * @property string $shipping_source
 * @property string $other_shipping_source
 * @property string $mobile
 * @property string $email
 * @property string $annual_revenue
 * @property string $reference
 * @property string $agreement
 * @property string $other_reference
 * @property string $country
 * @property string $selling_on_fruugo
 * @property string $selling_on_fruugo_source
 * @property string $other_selling_source
 * @property string $approved_by_fruugo
 */
class FruugoRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fruugo_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'integer'],
            [['fname', 'lname', 'store_name', 'email', 'annual_revenue', 'agreement', 'country', 'selling_on_fruugo'], 'required'],
            [['reference'], 'string'],
            [['fname', 'other_shipping_source'], 'string', 'max' => 200],
            [['lname', 'store_name', 'shipping_source', 'email', 'annual_revenue', 'agreement', 'other_reference', 'approved_by_fruugo'], 'string', 'max' => 255],
            [['mobile', 'selling_on_fruugo'], 'string', 'max' => 20],
        	['agreement', 'required', 'requiredValue' => 1, 'message' => 'You must agree to the terms and conditions.'],
            [['country', 'selling_on_fruugo_source', 'other_selling_source'], 'string', 'max' => 50],
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
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'store_name' => 'Store Name',
            'shipping_source' => 'Shipping Source',
            'other_shipping_source' => 'Other Shipping Source',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'annual_revenue' => 'Annual Revenue',
            'reference' => 'Reference',
            'agreement' => 'Agreement',
            'other_reference' => 'Other Reference',
            'country' => 'Country',
            'selling_on_fruugo' => 'Selling On fruugo',
            'selling_on_fruugo_source' => 'Selling On fruugo Source',
            'other_selling_source' => 'Other Selling Source',
            'approved_by_fruugo' => 'Approved By fruugo',
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
}
