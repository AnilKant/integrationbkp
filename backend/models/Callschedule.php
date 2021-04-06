<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "call_schedule".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $number
 * @property string $shop_url
 * @property string $marketplace
 * @property string $status
 * @property string $time
 * @property integer $no_of_request
 * @property string $time_zone
 * @property string $preferred_time
 * @property string $response
 */
class Callschedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'number', 'shop_url', 'marketplace', 'status', 'time', 'no_of_request', 'time_zone', 'preferred_timeslot', 'response'], 'required'],
            [['merchant_id', 'no_of_request'], 'integer'],
            [['time'], 'safe'],
            [['number'], 'string', 'max' => 100],
            [['shop_url', 'preferred_timeslot', 'response'], 'string', 'max' => 255],
            [['marketplace', 'status'], 'string', 'max' => 45],
            [['time_zone'], 'string', 'max' => 225],
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
            'number' => 'Number',
            'shop_url' => 'Shop Url',
            'marketplace' => 'Marketplace',
            'status' => 'Status',
            'time' => 'Time',
            'no_of_request' => 'No Of Request',
            'time_zone' => 'Time Zone',
            'preferred_timeslot' => 'Preferred Time',
            'response' => 'Response',
        ];
    }
}
