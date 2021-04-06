<?php

namespace backend\models;

use Yii;


class WalmartcaFaq extends \yii\db\ActiveRecord
{

 /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'walmartca_faqs';
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

    public static function getDb()
    {
        return Yii::$app->admin;
    }

}