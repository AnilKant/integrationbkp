<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "apps_latest_updates".
 *
 * @property integer $id
 * @property string $marketplace
 * @property string $title
 * @property string $description
 * @property string $is_enabled
 * @property string $created_at
 */
class AppsLatestUpdates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apps_latest_updates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_enabled'], 'string'],
            [['marketplace','title'], 'string', 'max' => 255],
            [['marketplace', 'title', 'description', 'is_enabled','expired_in'], 'required'],
            [['created_at','description'], 'safe'],
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
            'title' => 'Latest update title',
            'description' => 'Description',
            'is_enabled' => 'Is Enabled',
            'created_at' => 'Update time',
            'expired_in' => 'Expired In'
        ];
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
