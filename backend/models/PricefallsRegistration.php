<?php
namespace backend\models;

use Yii;
use common\models\Merchant;

class PricefallsRegistration extends \yii\db\ActiveRecord
{
    public $time_format;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pricefalls_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id','fname','lname','email','mobile','time_zone','time_slot','time_format', 'agreement', 'reference','shipping_source'], 'required'],
            [['merchant_id'], 'integer'],
            [['fname', 'lname', 'email','shipping_source', 'other_shipping_source','reference'], 'string', 'max' => 255],
            [['mobile'], 'number','message' => '"{value}" is invalid {attribute}. Only Numbers are allowed.'],
            [[ 'other_reference'], 'string'],
            [[ 'agreement'], 'string', 'max' => 10],
            ['agreement', 'required', 'requiredValue' => 1, 'message' => 'You must agree to the terms and conditions.'],
            /*['other_reference', 'required', 'message' => 'This this field cannot be blank.', 'when' => function ($model) {
                return $model->reference == 'Other';
            }, 'whenClient' => "function (attribute, value) {
                    return $('#pricefallsregistration-reference').val() == 'Other';
            }"],*/

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
            'mobile' => 'Mobile',
            'email' => 'Email',
            'time_zone' => 'Your Primary Time Zone',
            'time_slot' => 'Your Preffered Time Slot',
            'shipping_source' => 'Shipping Source',
            'other_shipping_source' => 'Other Shipping Source',
            'reference' => 'How did you hear about us?',
            'other_reference' => 'Other Reference',
            'agreement' => 'Agreement',
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