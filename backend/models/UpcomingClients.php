<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "upcoming_clients".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $description
 * @property string $is_checked
 * @property string $date
 */
class UpcomingClients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upcoming_clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'is_checked'], 'string'],
            [['date'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'description' => 'Description',
            'is_checked' => 'Is Checked',
            'date' => 'Date',
        ];
    }
}
