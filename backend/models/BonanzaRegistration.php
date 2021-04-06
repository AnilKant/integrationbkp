<?php
namespace backend\models;

use Yii;
use common\models\Merchant;

/**
 * This is the model class for table "bonanza_registration".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $fname
 * @property string $lname
 * @property string $store_name
 * @property string $email
 * @property string $mobile
 * @property string $reference
 * @property string $agreement
 * @property string $other_reference
 * @property string $selling_on_bonanza
 * @property string $selling_on_bonanza_source
 * @property string $other_selling_source
 *
 * @property MerchantDb $merchant
 */
class BonanzaRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bonanza_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'fname', 'lname', 'store_name', 'email', 'mobile', 'agreement', 'selling_on_bonanza'], 'required'],
            [['merchant_id'], 'integer'],
            [['reference'], 'string'],
            [['fname', 'lname', 'mobile',  'other_reference', 'selling_on_bonanza', 'selling_on_bonanza_source', 'other_selling_source'], 'string', 'max' => 255],
            [['store_name'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 225],
            ['agreement', 'required', 'requiredValue' => 1, 'message' => 'You must agree to the terms and conditions.'],
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
            'store_name' => 'Store URL',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'reference' => 'Reference',
            'agreement' => 'I Accept Terms & Conditions',
            'other_reference' => 'Other Reference',
            'selling_on_bonanza' => 'Selling On Bonanza',
            'selling_on_bonanza_source' => 'Selling On Bonanza Source',
            'other_selling_source' => 'Other Selling Source',
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
