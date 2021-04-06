<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "apps_faq".
 *
 * @property integer $id
 * @property string $marketplace
 * @property string $query
 * @property string $description
 * @property string $is_enabled
 * @property string $faq_type
 */
class AppsFaq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apps_faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description','is_enabled'], 'string'],
            [['marketplace', 'query','faq_type','faq_category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'marketplace' => 'Marketplace',
            'query' => 'Query',
            'faq_type'=>'FAQ Type',
            'faq_category'=>'FAQ Category',
            'description' => 'Description',
            'is_enabled' => 'Is Enabled',
        ];
    }
    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
