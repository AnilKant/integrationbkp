<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "jet_attributes".
 *
 * @property integer $id
 * @property string $display_name
 * @property string $description
 * @property integer $free_text
 * @property string $display
 * @property string $facet_filter
 * @property string $variant
 * @property string $variant_pair
 * @property string $attribute_values
 */
class JetAttributes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jet_attributes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'display_name', 'description', 'free_text', 'display', 'facet_filter', 'variant', 'variant_pair', 'attribute_values'], 'required'],
            [['id', 'free_text'], 'integer'],
            [['display_name', 'description', 'display', 'facet_filter', 'variant', 'variant_pair', 'attribute_values'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'display_name' => 'Display Name',
            'description' => 'Description',
            'free_text' => 'Free Text',
            'display' => 'Display',
            'facet_filter' => 'Facet Filter',
            'variant' => 'Variant',
            'variant_pair' => 'Variant Pair',
            'attribute_values' => 'Attribute Values',
        ];
    }
}
