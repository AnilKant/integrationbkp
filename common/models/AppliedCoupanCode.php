<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "applied_coupan_code".
 *
 * @property integer $id
 * @property integer $merchant_id
 * @property string $used_on
 * @property string $coupan_code
 * @property string $activated_date
 * @property integer $coupan_code_id
 */
class AppliedCoupanCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applied_coupan_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'used_on', 'coupan_code', 'coupan_code_id'], 'required'],
            [['merchant_id', 'coupan_code_id'], 'integer'],
            [['activated_date'], 'safe'],
            [['used_on', 'coupan_code'], 'string', 'max' => 255]
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
            'used_on' => 'Used On',
            'coupan_code' => 'Coupan Code',
            'activated_date' => 'Activated Date',
            'coupan_code_id' => 'Coupan Code ID',
        ];
    }
}
