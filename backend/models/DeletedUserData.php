<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "deleted_user_data".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $token
 * @property string $installed_on
 * @property string $shop_name
 * @property string $email
 * @property string $created_at
 * @property string $phone_number
 * @property string $country
 */
class DeletedUserData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deleted_user_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'token', 'installed_on', 'shop_name', 'email'], 'required'],
            [['merchant_id'], 'integer'],
            [['created_at'], 'safe'],
            [['token', 'installed_on', 'shop_name', 'email', 'phone_number', 'country'], 'string', 'max' => 255]
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
            'token' => 'Token',
            'installed_on' => 'Installed On',
            'shop_name' => 'Shop Name',
            'email' => 'Email',
            'created_at' => 'Created At',
            'phone_number' => 'Phone Number',
            'country' => 'Country',
        ];
    }
}
