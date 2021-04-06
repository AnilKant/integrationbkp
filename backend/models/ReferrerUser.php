<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "referrer_user".
 *
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property integer $merchant_id
 * @property string $code
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class ReferrerUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referrer_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'username', 'password', 'status'], 'required'],
            [['merchant_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'username'], 'string', 'max' => 200],
            [['password', 'code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'username' => 'Username',
            'password' => 'Password',
            'merchant_id' => 'Merchant ID',
            'code' => 'Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    public function approve()
    {
        if($this->status=='0' || $this->status=='2') {
            $this->status = 1;
            $this->save(false);
        }
        return true;
    }

    public function unapprove()
    {
        if($this->status=='0' || $this->status=='1') {
            $this->status = 2;
            $this->save(false);
        }
        return true;
    }
}
