<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Admin;

/**
 * Login form
 */
class AdminLoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    	//print_r(Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0)) ;die;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
        	
            $this->_user = Admin::findByUsername($this->username);
        }
		//print_r( $this->_user);die;
        return $this->_user;
    }
    public function signup()
    {
    	//if ($this->validate()) {
    		//print_r("validate");
    		$user = new Admin();
    		$user->username = $this->username;
    		//$user->email = $this->email;
    		$user->setPassword($this->password);
    		$user->generateAuthKey();
    		$user->save();
    		return $user;
    		
    	//}
    	/* $errors = $this->errors;
    	print_r($errors);die;
    	print_r('gsg');
    	return null; */
    }
}
