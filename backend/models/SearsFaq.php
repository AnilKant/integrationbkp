<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Sears_faq".
 *
 * @property integer $id
 * @property string $query
 * @property string $description
 */
class SearsFaq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sears_faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['query', 'description'], 'required'],
            [['query', 'description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'query' => 'Query',
            'description' => 'Description',
        ];
    }
}
