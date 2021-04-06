<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "conditional_range".
 *
 * @property integer $id
 * @property integer $charge_id
 * @property integer $fixed_range
 * @property integer $from_range
 * @property integer $to_range
 * @property varchar $amount_type
 * @property double $amount
 */
class ConditionalRange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conditional_range';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'charge_id'], 'integer'],
            [['amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'charge_id' => 'Charge ID',
            'fixed_range' => 'Fixed Range',
            'from_range' => 'From Range',
            'to_range' => 'To Range',
            'amount_type' => 'Amount Type',
            'amount' => 'Amount',
        ];
    }
}
