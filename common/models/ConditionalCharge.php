<?php

namespace common\models;

use Yii;
use common\models\ConditionalRange;

/**
 * This is the model class for table "conditional_charge".
 *
 * @property integer $id
 * @property string $charge_name
 * @property string $charge_condition
 * @property string $charge_range
 * @property string $merchant_base
 * @property string $charge_type
 * @property string $apply
 */
class ConditionalCharge extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $conditional_range;
    public static function tableName()
    {
        return 'conditional_charge';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge_name','charge_description','charge_condition', 'charge_range', 'merchant_base', 'charge_type', 'apply'], 'required'],
            [['charge_name', 'charge_condition', 'charge_range', 'conditional_range','merchant_base', 'charge_type', 'apply','merchants'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'charge_name' => 'Conditon Name',
            'charge_condition' => 'Charge Condition',
            'charge_range' => 'Charge Range',
            'merchant_base' => 'Apply For',
            'charge_type' => 'Charge Type',
            'apply' => 'Enable',
            'charge_description' => 'Condition Description'
        ];
    }
  
     public function getConditional_range()
    {
        return $this->hasOne(ConditionalRange::className(), [ 'charge_id' => 'id']);
    }

}
