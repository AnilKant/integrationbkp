<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "latest_updates".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $marketplace
 * @property string $created_at
 * @property string $updated_at
 */
class LatestUpdates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'latest_updates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'marketplace'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['marketplace'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'marketplace' => 'Marketplace',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /*public static function getDb()
    {
        return Yii::$app->get(Yii::$app->getBaseDb());
    }*/
}
