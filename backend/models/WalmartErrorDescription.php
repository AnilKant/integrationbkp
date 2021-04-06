<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "walmart_error_description".
 *
 * @property integer $id
 * @property integer $error_type
 * @property string $error_code
 * @property string $error_description
 * @property string $error_solution
 * @property integer $is_enable
 */
class WalmartErrorDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'walmart_error_description';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_enable'], 'integer'],
            [['error_code', 'error_description', 'error_solution'], 'string'],
            [['error_type', 'error_description','error_solution','error_code'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'error_type' => 'Error Type',
            'error_code' => 'Error Code',
            'error_description' => 'Error Description',
            'error_solution' => 'Error Solution',
            'is_enable' => 'Is Enable',
        ];
    }
}
