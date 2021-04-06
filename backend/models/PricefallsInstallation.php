<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class PricefallsInstallation extends ActiveRecord
{
    public static function tableName()
    {
        return 'pricefalls_installation';
    }

    public function rules()
    {
        return [
            [['merchant_id', 'status', 'step'], 'required'],
            [['merchant_id'], 'integer'],
            [['status'], 'string', 'max' => 100],
            [['step'], 'string', 'max' => 11],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'status' => 'Status',
            'step' => 'Step',
        ];
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }

}