<?php
    
    namespace backend\models;
    
    use Yii;
    use common\models\Merchant;
    use backend\models\BonanzaRegistration;
    
    /**
     * This is the model class for table "Bonanza_shop_details".
     *
     * @property integer $id
     * @property integer $merchant_id
     * @property string $token
     * @property string $install_status
     * @property string $installed_on
     * @property string $uninstall_date
     * @property string $uninstall_status
     * @property string $seller_username
     * @property string $seller_password
     * @property string $expire_date
     * @property string $purchase_status
     * @property integer $limit_renew_till
     * @property string $created_at
     * @property string $disable_cron
     *
     * @property MerchantDb $merchant
     */
    class Bonanzashopdetails extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'bonanza_shop_details';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['merchant_id'], 'integer'],
                [['expired_on', 'created_at', 'disable_cron', 'seller_username', 'seller_password','prod_import_limit', 'uninstall_date','limit_renew_till'], 'safe'],
                [['token', 'install_status','purchase_status'], 'string', 'max' => 255],
            
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
                'installed_on' => 'Installed On',
                'uninstall_date' => 'Uninstall Date',
                'uninstall_status' => 'Uninstall Status',
                'seller_username' => 'Seller Username',
                'seller_password' => 'seller_password',
                'prod_import_limit' => 'Product Import Limit',
                'expired_on' => 'Expired On',
                'purchase_status' => 'Purchase Status',
                'created_at' => 'Created At',
                'disable_cron' => 'Disable Cron',
            ];
        }
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getMerchant()
        {
            return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
        }
        
        public function getBonanzaRegistration()
        {
            return $this->hasOne(BonanzaRegistration::className(),['merchant_id' => 'merchant_id']);
        }
        
        public static function getDb()
        {
            return Yii::$app->admin;
        }
    }
