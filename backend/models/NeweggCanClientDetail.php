<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "newegg_registration".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $name
 * @property string $shipping_source
 * @property string $other_shipping_source
 * @property string $mobile
 * @property string $email
 * @property string $reference
 * @property string $agreement
 * @property string $other_reference
 */
class NeweggCanClientDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newegg_can_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'integer'],
            [['name', 'shipping_source', 'email', 'reference', 'agreement'], 'required'],
            [['reference'], 'string'],
            [['name', 'other_shipping_source'], 'string', 'max' => 200],
            [['shipping_source', 'email', 'agreement', 'other_reference'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 15]
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
            'name' => 'Name',
            'shipping_source' => 'Shipping Source',
            'other_shipping_source' => 'Other Shipping Source',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'reference' => 'Reference',
            'agreement' => 'Agreement',
            'other_reference' => 'Other Reference',
        ];
    }
}
