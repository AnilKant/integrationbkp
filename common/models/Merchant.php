<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 10/10/17
 * Time: 3:02 PM
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * Merchant model
 *
 * @property integer $id
 * @property string $merchant_id
 * @property string $shopurl
 * @property string $shopname
 * @property string $owner_name
 * @property string $email
 * @property string $currency
 * @property string $shop_json
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Merchant extends ActiveRecord implements IdentityInterface
{
    public $manager = null;
    public $phone;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;    

    public static function tableName()
    {
        return '{{%merchant}}';
    }

    /*public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }*/

    public function rules()
    {
        return [
            [['merchant_id', 'shopurl','status','shopname','owner_name','email','currency'], 'required'],
            [['shop_json'],'string'],
            [['merchant_id'], 'integer'],
            [['shopurl', 'shopname','owner_name', 'email'], 'string', 'max' => 200],
            [['currency'], 'string', 'max' => 50],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public static function findIdentity($id)
    {
//        print_r($merchant_id);die;
        $session = Yii::$app->getSession();

        $managerLoginIndex = 'manager_login_'.$id;
//        print_r($managerLoginIndex);die;
        $data = $session->get($managerLoginIndex);
        if ($data)
        {
            $user = static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
            //$user->manager = true;

            return $user;
        }
        else
        {
            return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        }
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($shopurl)
    {
        return static::findOne(['shopurl' => $shopurl, 'status' => self::STATUS_ACTIVE]);
    }

    public function validateAuthKey($authKey)
    {
        //return $this->getAuthKey() === $authKey;
        return;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        //return $this->auth_key;
        return;
    }

    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
