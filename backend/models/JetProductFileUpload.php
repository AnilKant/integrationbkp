<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "jet_product_file_upload".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $local_file_path
 * @property string $file_name
 * @property string $file_type
 * @property string $file_url
 * @property string $jet_file_id
 * @property string $received
 * @property string $processing_start
 * @property string $processing_end
 * @property integer $total_processed
 * @property integer $error_count
 * @property string $error_url
 * @property string $error_excerpt
 * @property integer $expires_in_seconds
 * @property string $file_upload_time
 * @property string $error
 * @property string $status
 *
 * @property JetShopDetails $merchant
 */
class JetProductFileUpload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jet_product_file_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'required'],
            [['merchant_id', 'total_processed', 'error_count', 'expires_in_seconds'], 'integer'],
            [['file_url', 'error_url', 'error_excerpt', 'error'], 'string'],
            [['file_upload_time'], 'safe'],
            [['local_file_path', 'file_name', 'file_type', 'jet_file_id', 'received', 'processing_start', 'processing_end', 'status'], 'string', 'max' => 255]
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
            'local_file_path' => 'Local File Path',
            'file_name' => 'File Name',
            'file_type' => 'File Type',
            'file_url' => 'File Url',
            'jet_file_id' => 'Jet File ID',
            'received' => 'Received',
            'processing_start' => 'Processing Start',
            'processing_end' => 'Processing End',
            'total_processed' => 'Total Processed',
            'error_count' => 'Error Count',
            'error_url' => 'Error Url',
            'error_excerpt' => 'Error Excerpt',
            'expires_in_seconds' => 'Expires In Seconds',
            'file_upload_time' => 'File Upload Time',
            'error' => 'Error',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(JetShopDetails::className(), ['merchant_id' => 'merchant_id']);
    }
}
