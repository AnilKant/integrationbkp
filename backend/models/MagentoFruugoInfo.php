<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "magento_fruugo_info".
 *
 * @property integer $id
 * @property string $domain
 * @property string $email
 * @property integer $live_sku
 * @property integer $uploaded_sku
 * @property string $total_revenue
 * @property integer $config_set
 * @property string $install_on
 * @property string $framework
 */
class MagentoFruugoInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'magento_fruugo_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain', 'email', 'framework'], 'required'],
            [['live_sku', 'uploaded_sku'], 'integer'],
            [['total_revenue'], 'string'],
            [['install_on','fruugo_username','fruugo_password'], 'safe'],
            [['domain', 'email', 'framework'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Domain',
            'email' => 'Email',
            'live_sku' => 'Live Sku',
            'uploaded_sku' => 'Uploaded Sku',
            'total_revenue' => 'Total Revenue',
            'last_synced' => 'Last Synced',
            'install_on' => 'Install On',
            'framework' => 'Framework',
        ];
    }
}
