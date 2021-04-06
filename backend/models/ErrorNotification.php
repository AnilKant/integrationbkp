<?php

namespace backend\models;

use Yii;
use frontend\modules\walmart\components\TableConstant;

/**
 * This is the model class for table "error_notification".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $identifier
 * @property string $identifier_type
 * @property string $marketplace
 * @property integer $error_type
 * @property string $reason
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 */
class ErrorNotification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return TableConstant::WALMART_ERROR_NOTIFICATION;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'required'],
            [['merchant_id', 'error_type'], 'integer'],
            [['reason', 'data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['identifier', 'identifier_type', 'marketplace'], 'string', 'max' => 255]
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
            'identifier' => 'Identifier',
            'identifier_type' => 'Identifier Type',
            'marketplace' => 'Marketplace',
            'error_type' => 'Error Type',
            'reason' => 'Reason',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
