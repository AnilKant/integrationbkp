<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staff_account_members".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $email
 * @property string $password
 * @property string $created_at
 */
class StaffAccountMembers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_account_members';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'email', 'password'], 'required'],
            [['merchant_id'], 'integer'],
            [['created_at'], 'safe'],
            [['email', 'password'], 'string', 'max' => 255],
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
            'email' => 'Email',
            'password' => 'Password',
            'created_at' => 'Created At',
        ];
    }
}
