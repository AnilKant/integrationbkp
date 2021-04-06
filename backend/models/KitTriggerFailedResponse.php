<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kit_trigger_failed_response".
 *
 * @property integer $id
 * @property string $shop_name
 * @property string $skill_name
 * @property string $response
 * @property string $updated_at
 * @property string $created_at
 * @property string $app_name
 *
 * @property MerchantDb $shopName
 */
class KitTriggerFailedResponse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kit_trigger_failed_response';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('admin');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_name'], 'required'],
            [['response'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['shop_name'], 'string', 'max' => 255],
            [['skill_name'], 'string', 'max' => 10],
            [['app_name'], 'string', 'max' => 20],
            [['skill_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_name' => 'Shop Name',
            'skill_name' => 'Skill Name',
            'response' => 'Response',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'app_name' => 'App Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopName()
    {
        return $this->hasOne(MerchantDb::className(), ['shop_name' => 'shop_name']);
    }
}
