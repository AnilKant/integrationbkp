<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "merchant_db".
 *
 * @property integer $merchant_id
 * @property string $shop_name
 * @property string $db_name
 * @property string $app_name
 */
class MerchantDb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'merchant_db';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_name', 'db_name', 'app_name'], 'required'],
            [['shop_name'], 'string', 'max' => 255],
            [['db_name', 'app_name'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'merchant_id' => 'Merchant ID',
            'shop_name' => 'Shop Name',
            'db_name' => 'Db Name',
            'app_name' => 'App Name',
        ];
    }
    public static function getDb()
    {
        return Yii::$app->get(Yii::$app->getBaseDb());
    }   
}