<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "common_notification".
 *
 * @property integer $id
 * @property string $html_content
 * @property integer $sort_order
 * @property string $from_date
 * @property string $to_date
 * @property string $enable
 * @property string $marketplace
 */
class CommonNotification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'common_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['html_content','heading'], 'string'],
            [['sort_order', 'from_date', 'to_date','heading'], 'required'],
            [['sort_order'], 'integer'],
            [['from_date', 'to_date','marketplace','heading'], 'safe'],
            [['enable'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'heading' => 'Heading',
            'html_content' => 'Html Content',
            'sort_order' => 'Sort Order',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'enable' => 'Enable',
            'marketplace' => 'Marketplace',
        ];
    }
}
