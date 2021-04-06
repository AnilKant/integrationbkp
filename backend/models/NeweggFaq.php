<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 8/3/18
 * Time: 5:22 PM
 */

namespace backend\models;


class NeweggFaq extends \yii\db\ActiveRecord
{

 /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newegg_faq';
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
            'marketplace' => 'Marketplace'
        ];
    }

}