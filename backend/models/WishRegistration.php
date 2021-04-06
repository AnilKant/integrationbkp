<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wish_registration".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $fname
 * @property string $lname
 * @property string $email
 * @property integer $mobile
 * @property string $country
 * @property string $time_zone
 * @property string $time_slot
 * @property integer $agreement
 * @property string $reference
 * @property string $other_reference
 * @property string $shipping_source
 * @property string $other_shipping_source
 * @property string $created_at
 */
class WishRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wish_registration';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('admin');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'merchant_id', 'mobile', 'agreement'], 'integer'],
            [['email', 'reference', 'other_reference', 'shipping_source', 'other_shipping_source'], 'string'],
            [['created_at'], 'safe'],
            [['fname', 'lname', 'country', 'time_zone', 'time_slot'], 'string', 'max' => 255]
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
            'fname' => 'Fname',
            'lname' => 'Lname',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'country' => 'Country',
            'time_zone' => 'Time Zone',
            'time_slot' => 'Time Slot',
            'agreement' => 'Agreement',
            'reference' => 'Reference',
            'other_reference' => 'Other Reference',
            'shipping_source' => 'Shipping Source',
            'other_shipping_source' => 'Other Shipping Source',
            'created_at' => 'Created At',
        ];
    }
}
