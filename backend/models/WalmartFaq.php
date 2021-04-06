<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "walmart_faq".
 *
 * @property integer $id
 * @property string $query
 * @property string $description
 */
class WalmartFaq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'walmart_faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['query', 'description'], 'required'],
            [['query', 'description'], 'string']
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
