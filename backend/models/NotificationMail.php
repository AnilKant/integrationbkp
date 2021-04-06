<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "notification_mail".
 *
 * @property integer $id
 * @property string $mail_type
 * @property string $days
 * @property integer $send_mail
 * @property string $marketplace
 * @property string $email_template
 */
class NotificationMail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_mail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail_type', 'days', 'send_mail', 'marketplace', 'email_template','subject'], 'required'],
            [['send_mail'], 'integer'],
            [['mail_type'], 'string', 'max' => 100],
            [['days', 'marketplace', 'email_template'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mail_type' => 'Mail Type',
            'days' => 'Days',
            'send_mail' => 'Send Mail',
            'marketplace' => 'Marketplace',
            'email_template' => 'Email Template',
            'subject' => 'Subject'
        ];
    }
}
