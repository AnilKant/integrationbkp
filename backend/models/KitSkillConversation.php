<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kit_skill_conversation".
 *
 * @property integer $id
 * @property string $shop_name
 * @property string $conversation_uid
 * @property string $type
 * @property string $response
 * @property integer $status
 * @property string $updated_at
 * @property string $created_at
 * @property string $app_name
 * @property string $skill_name
 *
 * @property MerchantDb $shopName
 */
class KitSkillConversation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kit_skill_conversation';
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
            [['shop_name', 'conversation_uid'], 'required'],
            [['response'], 'string'],
            [['status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['shop_name'], 'string', 'max' => 255],
            [['conversation_uid', 'skill_name'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 10],
            [['app_name'], 'string', 'max' => 20],
            [['conversation_uid'], 'unique']
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
            'conversation_uid' => 'Conversation Uid',
            'type' => 'Type',
            'response' => 'Response',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'app_name' => 'App Name',
            'skill_name' => 'Skill Name',
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
