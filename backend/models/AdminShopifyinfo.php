<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_shopifyinfo".
 *
 * @property integer $id
 * @property string $api_key
 * @property string $secret_key
 * @property string $scope
 */
class AdminShopifyinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_shopifyinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['api_key', 'secret_key', 'scope'], 'required'],
            [['api_key', 'secret_key', 'scope'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'api_key' => 'Api Key',
            'secret_key' => 'Secret Key',
            'scope' => 'Scope',
        ];
    }
}
