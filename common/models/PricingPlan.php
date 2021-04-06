<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pricing_plan".
 *
 * @property integer $id
 * @property string $plan_name
 * @property string $plan_type
 * @property string $duration
 * @property string $plan_status
 * @property integer $base_price
 * @property integer $special_price
 * @property integer $apply_on
 * @property string $additional_condition
 */
class PricingPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pricing_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_price', 'special_price', 'apply_on','trial_period'], 'required'],
            [['base_price', 'special_price','trial_period'], 'integer'],
            [['plan_name', 'plan_type', 'duration', 'plan_status'], 'string', 'max' => 255],
            [['feature'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_name' => 'Plan Name',
            'plan_type' => 'Plan Type',
            'duration' => 'Duration',
            'plan_status' => 'Plan Status',
            'base_price' => 'Base Price',
            'special_price' => 'Special Price',
            'apply_on' => 'Apply On',
            'trial_period' => 'Free Trail',
            'additional_condition' => 'Additional Condition',
        ];
    }
}
