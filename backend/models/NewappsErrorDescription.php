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
 * @property string $marketplace
 * @property integer $is_enable
 */
class NewappsErrorDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newapps_error_description';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['error_type', 'is_enable'], 'integer'],
            [['marketplace','error_code', 'error_description', 'error_solution'], 'safe']
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
            'marketplace' => 'Marketplace',
            'error_description' => 'Error Description',
            'error_solution' => 'Error Solution',
            'is_enable' => 'Is Enable',
        ];
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
