<?php

namespace backend\models;

use Yii;
use common\models\Merchant;

/**
 * This is the model class for table "bestbuyca_shop_details".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $token
 * @property string $install_status
 * @property string $install_date
 * @property string $uninstall_date
 * @property string $purchase_status
 * @property string $expire_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_login_ip
 * @property string $last_login_time
 *
 * @property MerchantDb $merchant
 */
class BestbuycaShopDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bestbuyca_shop_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'integer'],
            [['install_date', 'uninstall_date', 'expire_date', 'created_at', 'updated_at', 'last_login_time',
            'import_renew_till'], 'safe'],
            [['token', 'install_status', 'purchase_status'], 'string', 'max' => 255],
            [['last_login_ip'], 'string', 'max' => 225],
           /* [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => MerchantDb::className(), 'targetAttribute' => ['merchant_id' => 'merchant_id']],*/
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
            'install_status' => 'Install Status',
            'install_date' => 'Install Date',
            'uninstall_date' => 'Uninstall Date',
            'purchase_status' => 'Purchase Status',
            'expire_date' => 'Expire Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_ip' => 'Last Login Ip',
            'last_login_time' => 'Last Login Time',
            'import_renew_till'=>'Import Renew Till'
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
