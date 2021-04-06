<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "magento_extension_grid_details".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $details
 * @property string $last_updated
 */
class MagentoExtensionGridDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'magento_extension_grid_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'details'], 'required'],
            [['client_id'], 'integer'],
            [['details'], 'string'],
            [['last_updated'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'details' => 'Details',
            'last_updated' => 'Last Updated',
        ];
    }
}
