<?php

namespace backend\models;
use Yii;


class WishFaq extends \yii\db\ActiveRecord
{

 /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wish_faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['query', 'description'], 'required'],
            [['query', 'description'], 'string'],
           /* [['marketplace'],'each', 'in', 'range' => array_keys(['abc','def'])]*/
          //  [['marketplace'], 'exist','allowArray' => true,'when' => function ($model, $attribute) { return is_array($model->$attribute);}],



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
            // 'marketplace' => 'Marketplace'
        ];
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }

}